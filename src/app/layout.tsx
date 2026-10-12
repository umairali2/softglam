import type { Metadata, Viewport } from "next";
import { Geist, Cormorant_Garamond } from "next/font/google";
import { Footer } from "@/components/footer";
import { Header } from "@/components/header";
import { siteConfig } from "@/lib/site";
import "./globals.css";

const sans = Geist({ subsets: ["latin"], variable: "--font-sans", display: "swap" });
const serif = Cormorant_Garamond({ subsets: ["latin"], variable: "--font-serif", display: "swap", weight: ["400", "500"] });

export const metadata: Metadata = {
  metadataBase: new URL(siteConfig.url),
  title: { default: "Softglam — Objects for every day", template: "%s | Softglam" },
  description: siteConfig.description,
  keywords: ["jewellery", "eyewear", "accessories", "modern essentials", "Softglam"],
  alternates: { canonical: "/" },
  openGraph: {
    type: "website",
    siteName: siteConfig.name,
    title: "Softglam — Objects for every day",
    description: siteConfig.description,
    url: "/",
  },
  twitter: { card: "summary_large_image", title: "Softglam — Objects for every day", description: siteConfig.description },
  robots: { index: true, follow: true, googleBot: { index: true, follow: true, "max-image-preview": "large", "max-snippet": -1 } },
};

export const viewport: Viewport = { width: "device-width", initialScale: 1, themeColor: "#fbfaf8" };

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return <html lang="en" className={`${sans.variable} ${serif.variable}`}>
    <body>
      <a className="skip-link" href="#main">Skip to content</a>
      <Header />
      {children}
      <Footer />
    </body>
  </html>;
}
