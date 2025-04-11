import { cn } from "@/lib/utils";
import { Link } from "@inertiajs/react";

interface InertiaButtonProps {
  href: string;
  children: React.ReactNode;
  className?: string;
}

export function LinkButton({ href, children, className, ...props }: InertiaButtonProps) {
  return (
    <Link
      href={href}
      className={cn(
        "inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 px-2 py-1",
        className
      )}
      {...props}
    >
      {children}
    </Link>
  );
}
