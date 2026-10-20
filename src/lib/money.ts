/** Format integer minor units as a PKR display string. */
export function formatPkr(minorUnits: number): string {
  return `Rs ${(minorUnits / 100).toLocaleString("en-PK", { maximumFractionDigits: 0 })}`;
}
