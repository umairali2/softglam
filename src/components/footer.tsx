import Link from "next/link";
import { ArrowIcon } from "@/components/icons";

const footerLinks = {
  Shop: ["New arrivals", "Jewellery", "Eyewear", "Accessories"],
  Help: ["Contact us", "Delivery & returns", "Care guide", "FAQs"],
  About: ["Our story", "Materials", "Journal", "Privacy"],
};

export function Footer() {
  return <footer className="footer">
    <div className="footer-top shell">
      <div className="newsletter">
        <p className="eyebrow">Stay in the know</p>
        <h2>Notes from Softglam</h2>
        <p>New objects, considered stories and occasional offers—sent with restraint.</p>
        <form className="newsletter-form">
          <label className="sr-only" htmlFor="email">Email address</label>
          <input id="email" name="email" type="email" placeholder="Email address" autoComplete="email" required />
          <button type="submit" aria-label="Subscribe"><ArrowIcon /></button>
        </form>
      </div>
      <div className="footer-links">
        {Object.entries(footerLinks).map(([heading, links]) => <div key={heading}>
          <h3>{heading}</h3>
          {links.map((link) => <Link href="#" key={link}>{link}</Link>)}
        </div>)}
      </div>
    </div>
    <div className="footer-bottom shell">
      <Link className="wordmark" href="/">SOFTGLAM</Link>
      <p>Designed for the details that make you, you.</p>
      <p>© {new Date().getFullYear()} Softglam</p>
    </div>
  </footer>;
}
