import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function getTahunOptions(): { label: string; value: string }[] {
    const currentYear = new Date().getFullYear();
    const startYear = Math.max(2025, currentYear - 4);

    const options = [];
    for (let year = startYear; year <= currentYear; year++) {
      options.push({ label: year.toString(), value: year.toString() });
    }

    return options;
}
export const mapToOptions = (data: any[]) => data.map(item => ({ label: item.nama, value: item.id }));
  