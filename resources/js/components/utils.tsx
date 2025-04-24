import { toast } from 'sonner';

export const alertApp = (e: any, type: 'error' | null = null) => {
    const message = e.props?.flash ?? e;
    const isObject = typeof message === 'object' && message !== null;
    const description = isObject ? message.success ?? message.error ?? 'Terjadi kesalahan pada saat proses data' : message;
    const isError = type === 'error' || (isObject && message.error);
    toast[isError ? 'error' : 'success'](isError ? 'Galat' : 'Selamat', { description });
};
export const formLabel = (id: string) => {
    return id.replace(/([a-z])([A-Z])/g, "$1 $2").replace(/_/g, " ").replace(/^./, (str) => str.toUpperCase());
};

export const truncateText = (title: string, status = true,maxLength = 18) => {
    return status && title.length > maxLength ? title.slice(0, maxLength) + ' ...' : title;
};

export type YearOption = {
  label: number;
  value: number;
};

export const getYearOptions = (
    maxLength: number = 3,
    baseYear: number = 2025
): YearOption[] => {
    const currentYear = new Date().getFullYear();
    const years: YearOption[] = [];
    if (currentYear >= baseYear) {
        const startYear = Math.max(baseYear, currentYear - maxLength + 1);
        for (let year = startYear; year <= currentYear; year++) {
        years.push({
            label: year,
            value: year
        });
        }
    }
    return years;
};


