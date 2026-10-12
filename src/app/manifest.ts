import type { MetadataRoute } from "next";

export default function manifest(): MetadataRoute.Manifest {
  return {
    name: "Softglam",
    short_name: "Softglam",
    description: "Considered jewellery, eyewear and accessories designed for every day.",
    start_url: "/",
    display: "standalone",
    background_color: "#fbfaf8",
    theme_color: "#fbfaf8",
  };
}
