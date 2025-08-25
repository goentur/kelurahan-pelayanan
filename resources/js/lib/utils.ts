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

export const convertTextToSymbol = (text: string, symbol: string = '*'): string => {
  if (text.length <= 6) {
    // Jika panjang teks <= 6, maka 3 depan + 3 belakang akan overlap atau memakan seluruh teks
    // Jadi kita kembalikan teks asli
    return text;
  }

  const start = text.slice(0, 3); // 3 karakter pertama
  const end = text.slice(-3);     // 3 karakter terakhir
  const middle = text.slice(3, -3); // Bagian tengah yang akan diproses

  // Proses bagian tengah: ubah huruf/angka ke simbol, spasi tetap
  const maskedMiddle = middle
    .split('')
    .map(char => {
      if (/[a-zA-Z0-9]/.test(char)) {
        return symbol;
      }
      return char; // spasi atau karakter non-alfanumerik tetap
    })
    .join('');

  return start + maskedMiddle + end;
};