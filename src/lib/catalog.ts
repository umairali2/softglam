export type Product = {
  name: string;
  category: string;
  price: string;
  tone: string;
  badge?: string;
};

export const products: Product[] = [
  { name: "Sculpted Signet", category: "Jewellery", price: "$128", tone: "sand", badge: "New" },
  { name: "Fine Line Hoops", category: "Jewellery", price: "$94", tone: "pearl" },
  { name: "The No. 01 Frame", category: "Eyewear", price: "$165", tone: "ink", badge: "Bestseller" },
  { name: "Soft Chain", category: "Jewellery", price: "$118", tone: "clay" },
];

export const collections = [
  { name: "Everyday jewellery", description: "Quiet pieces made to live in.", tone: "warm", id: "jewellery" },
  { name: "Modern eyewear", description: "Clear lines. Distinct point of view.", tone: "cool", id: "eyewear" },
  { name: "Finishing touches", description: "The detail that brings it together.", tone: "neutral", id: "accessories" },
];
