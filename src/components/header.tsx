import Link from "next/link";
import { BagIcon, MenuIcon, SearchIcon, UserIcon } from "@/components/icons";

const navigation = [
  { label: "New in", href: "#new" },
  { label: "Jewellery", href: "#jewellery" },
  { label: "Eyewear", href: "#eyewear" },
  { label: "Accessories", href: "#accessories" },
  { label: "Our story", href: "#story" },
];

export function Header() {
  return <>
    <div className="announcement">Complimentary delivery on orders over $75</div>
    <header className="site-header">
      <div className="header-inner">
        <button className="icon-button menu-button" aria-label="Open menu"><MenuIcon /></button>
        <Link className="wordmark" href="/" aria-label="Softglam home">SOFTGLAM</Link>
        <nav className="desktop-nav" aria-label="Main navigation">
          {navigation.map((item) => <Link href={item.href} key={item.label}>{item.label}</Link>)}
        </nav>
        <div className="header-actions">
          <button className="icon-button search-button" aria-label="Search"><SearchIcon /></button>
          <Link className="icon-button account-button" href="/account" aria-label="Your account"><UserIcon /></Link>
          <Link className="icon-button bag-button" href="/cart" aria-label="Shopping bag"><BagIcon /></Link>
        </div>
      </div>
    </header>
  </>;
}
