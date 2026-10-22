const API_URL = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000/api/v1";
const APP_URL = API_URL.replace(/\/api\/v1\/?$/, "");

export type Money = { amount: number; currency: string };

export type ApiProduct = {
  id: string;
  slug: string;
  title: string;
  subtitle: string | null;
  short_description: string | null;
  brand: { name: string; slug: string } | null;
  categories: { name: string; slug: string }[];
  variants: ApiVariant[];
  images?: { id: string; url: string; alt: string | null }[];
};

export type ApiVariant = {
  id: string;
  sku: string;
  title: string | null;
  price: { amount: number; compare_at_amount: number | null; currency: string };
  availability?: { status: "in_stock" | "out_of_stock" };
  is_default: boolean;
};

export type CartItem = {
  id: string;
  quantity: number;
  unit_price: Money;
  line_total: Money;
  variant: {
    id: string;
    sku: string;
    title: string | null;
    in_stock: boolean;
    product: { slug: string; title: string };
  };
};

export type Cart = {
  id: string;
  items: CartItem[];
  totals: { item_count: number; subtotal_amount: number; currency: string };
};

export type OrderItem = {
  id: string;
  sku: string;
  product_title: string;
  variant_title: string | null;
  quantity: number;
  unit_price: Money;
  line_total: Money;
};

export type Order = {
  id: string;
  order_number: string;
  status: string;
  payment_method: string;
  contact_email: string;
  contact_phone: string;
  shipping_address: {
    recipient_name: string;
    phone: string;
    line1: string;
    line2?: string | null;
    area?: string | null;
    city: string;
    postal_code?: string | null;
  };
  items: OrderItem[];
  totals: { subtotal_amount: number; shipping_amount: number; total_amount: number; currency: string };
  verification_deadline_at: string | null;
  created_at: string | null;
};

export class ApiError extends Error {
  constructor(
    public status: number,
    message: string,
    public errors?: Record<string, string[]>,
  ) {
    super(message);
  }
}

async function parseResponse<T>(response: Response): Promise<T> {
  if (!response.ok) {
    const body = await response.json().catch(() => null);
    throw new ApiError(response.status, body?.message ?? `Request failed (${response.status})`, body?.errors);
  }
  if (response.status === 204) return undefined as T;
  return (await response.json()) as T;
}

let csrfPromise: Promise<void> | null = null;

async function ensureCsrfCookie(): Promise<void> {
  if (typeof document === "undefined") return;
  if (document.cookie.includes("XSRF-TOKEN=")) return;
  csrfPromise ??= fetch(`${APP_URL}/sanctum/csrf-cookie`, { credentials: "include" }).then(() => undefined);
  await csrfPromise;
  csrfPromise = null;
}

function xsrfHeader(): Record<string, string> {
  const match = document.cookie.match(/(?:^|; )XSRF-TOKEN=([^;]*)/);
  return match ? { "X-XSRF-TOKEN": decodeURIComponent(match[1]) } : {};
}

export async function apiGet<T>(path: string): Promise<T> {
  const response = await fetch(`${API_URL}${path}`, { credentials: "include", cache: "no-store" });
  return parseResponse<T>(response);
}

export async function apiSend<T>(method: "POST" | "PUT" | "PATCH" | "DELETE", path: string, body?: unknown): Promise<T> {
  await ensureCsrfCookie();
  const response = await fetch(`${API_URL}${path}`, {
    method,
    credentials: "include",
    headers: { "Content-Type": "application/json", Accept: "application/json", ...xsrfHeader() },
    body: body === undefined ? undefined : JSON.stringify(body),
  });
  return parseResponse<T>(response);
}

// Catalog
export const listProducts = (options: { category?: string; q?: string } = {}) =>
  apiGet<{ data: ApiProduct[] }>(
    `/products?${new URLSearchParams(
      Object.entries(options).filter(([, v]) => v) as [string, string][],
    ).toString()}`,
  );
export const getProduct = (slug: string) => apiGet<{ data: ApiProduct }>(`/products/${slug}`);

// Cart (cookie-session based)
export const getCart = () => apiGet<{ data: Cart }>("/cart");
export const addCartItem = (product_variant_id: string, quantity: number) =>
  apiSend<{ data: Cart }>("POST", "/cart/items", { product_variant_id, quantity });
export const updateCartItem = (itemId: string, quantity: number) =>
  apiSend<{ data: Cart }>("PATCH", `/cart/items/${itemId}`, { quantity });
export const removeCartItem = (itemId: string) => apiSend<void>("DELETE", `/cart/items/${itemId}`);

// Saved addresses
export type CustomerAddress = {
  id: string;
  recipient_name: string;
  phone: string;
  line1: string;
  line2: string | null;
  area: string | null;
  city: string;
  postal_code: string | null;
  is_default: boolean;
};

export const listAddresses = () => apiGet<{ data: CustomerAddress[] }>("/me/addresses");
export const createAddress = (payload: Omit<CustomerAddress, "id">) =>
  apiSend<{ data: CustomerAddress }>("POST", "/me/addresses", payload);
export const deleteAddress = (id: string) => apiSend<void>("DELETE", `/me/addresses/${id}`);

// Wishlist
export const listWishlist = () => apiGet<{ data: ApiProduct[] }>("/me/wishlist");
export const addToWishlist = (productId: string) =>
  apiSend<void>("POST", "/me/wishlist", { product_id: productId });
export const removeFromWishlist = (productId: string) =>
  apiSend<void>("DELETE", `/me/wishlist/${productId}`);

// Checkout
export const checkoutCod = (payload: {
  contact_email: string;
  contact_phone: string;
  address_id?: string;
  address?: {
    recipient_name: string;
    phone: string;
    line1: string;
    line2?: string;
    area?: string;
    city: string;
    postal_code?: string;
  } | undefined;
}) => apiSend<{ data: Order }>("POST", "/checkout", payload);

// Orders
export const getOrder = (orderNumber: string) => apiGet<{ data: Order }>(`/orders/${orderNumber}`);
export const cancelMyOrder = (orderNumber: string) =>
  apiSend<{ data: Order }>("POST", `/orders/${orderNumber}/cancel`);
export const listMyOrders = () => apiGet<{ data: Order[] }>("/me/orders");

// Auth
export type AuthUser = {
  id: string;
  name: string;
  email: string;
  email_verified_at: string | null;
  is_staff: boolean;
};

export const getUser = () => apiGet<{ data: AuthUser }>("/auth/user");
export const register = (payload: { name: string; email: string; password: string; password_confirmation: string }) =>
  apiSend<{ data: AuthUser }>("POST", "/auth/register", payload);
export const login = (payload: { email: string; password: string }) =>
  apiSend<{ data: AuthUser }>("POST", "/auth/login", payload);
export const logout = () => apiSend<void>("POST", "/auth/logout");
export const forgotPassword = (email: string) =>
  apiSend<{ data: { message: string } }>("POST", "/auth/forgot-password", { email });
export const resetPassword = (payload: { token: string; email: string; password: string; password_confirmation: string }) =>
  apiSend<{ data: { status: string } }>("POST", "/auth/reset-password", payload);

// Admin
export const listAdminOrders = (status?: string) =>
  apiGet<{ data: Order[] }>(`/admin/orders${status ? `?status=${status}` : ""}`);
export const confirmOrder = (orderId: string) =>
  apiSend<{ data: Order }>("POST", `/admin/orders/${orderId}/confirm`);
export const declineOrder = (orderId: string, reason: string) =>
  apiSend<{ data: Order }>("POST", `/admin/orders/${orderId}/decline`, { reason });
export const fulfillOrder = (orderId: string, payload: { courier_name?: string; tracking_number?: string }) =>
  apiSend<{ data: Order }>("POST", `/admin/orders/${orderId}/fulfill`, payload);
export const cancelOrder = (orderId: string, reason: string) =>
  apiSend<{ data: Order }>("POST", `/admin/orders/${orderId}/cancel`, { reason });
