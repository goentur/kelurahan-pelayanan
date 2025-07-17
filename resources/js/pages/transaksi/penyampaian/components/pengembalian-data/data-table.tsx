import { useState } from 'react';
import { Checkbox } from '@/components/ui/checkbox';
import { DatabaseBackup, Loader2, Printer } from 'lucide-react';
import axios from 'axios';
import { alertApp } from '@/components/utils';
import PerPageSelect from '@/components/data-table/per-page-select';
import { Button } from '@/components/ui/button';

type DataTableProps = {
    setInfoDataTabel: React.Dispatch<React.SetStateAction<any>>
    dataTable: any[];
    loading: boolean;
    data: any;
};

export default function DataTable({ setInfoDataTabel, dataTable, loading, data }: DataTableProps) {
    const [checkedItems, setCheckedItems] = useState<string[]>([]);
    const [loadingCetakKolektif, setLoadingCetakKolektif] = useState(false);

    const getNOP = (value: any) => {
        return `${value.id}`;
    };

    const allChecked = dataTable.length > 0 && checkedItems.length === dataTable.length;
    const handleSelectAll = (checked: boolean) => {
        if (checked) {
            const allNop = dataTable.map(getNOP);
            setCheckedItems(allNop);
        } else {
            setCheckedItems([]);
        }
    };

    const handleCetak = async () => {
        setLoadingCetakKolektif(true)
        try {
            const response = await axios.post(
                route('transaksi.penyampaian.pengembalian.cetak-massal-ba'),
                { nop: checkedItems, tahun: data.tahun },
                { responseType: 'blob' }
            );
            
            const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'berita-acara-massal.pdf');
            document.body.appendChild(link);
            link.click();
        } catch (err) {
            alertApp('Gagal mencetak dokumen', 'error');
        }finally{
            setLoadingCetakKolektif(false)
        }
    };

    const handleSelectOne = (nop: string) => {
        setCheckedItems((prev) => {
            if (prev.includes(nop)) {
                return prev.filter((item) => item !== nop);
            } else {
                return [...prev, nop];
            }
        });
    };

    return (
        <>
        <div className="flex items-center gap-2 mb-4 w-fit">
            <PerPageSelect
                onChange={(value) => {
                    setInfoDataTabel((prev: any) => ({
                        ...prev,
                        page: 1,
                        perPage: value,
                    })),
                    setCheckedItems([])
                }}
            />
            {checkedItems.length > 1 && 
            <Button type="button" disabled={loadingCetakKolektif} onClick={handleCetak}>
                {loadingCetakKolektif ? <Loader2 className="animate-spin" /> : <Printer/>} Cetak Kolektif Berita Acara
            </Button>
            }
        </div>
        <table className="w-full text-left border-collapse border">
            <thead className="text-center text-sm">
                <tr className="uppercase leading-normal">
                    <th className="px-2 border w-1">
                        <Checkbox 
                            checked={allChecked}
                            onCheckedChange={(checked) => handleSelectAll(!!checked)}
                        />
                    </th>
                    <th colSpan={4} className="px-2 border w-1">NOP <button onClick={handleCetak}>cetak</button></th>
                    <th className="px-2 border w-1/6">Nama</th>
                    <th className="px-2 border">Alamat</th>
                    <th className="px-2 border w-1">Pajak</th>
                    <th className="px-2 border w-1/5">Keterangan</th>
                    <th className="px-2 border w-1">Aksi</th>
                </tr>
            </thead>
            <tbody className="font-light text-xs">
                {loading && (
                    <tr>
                        <td className='p-1' colSpan={10}>
                            <div className="flex items-center justify-center">
                                <Loader2 className="animate-spin me-2" size={18} />Mohon Tunggu...
                            </div>
                        </td>
                    </tr>
                )}
                {dataTable.length > 0 ? (
                    dataTable.map((value: any, index: number) => {
                        const nop = getNOP(value);
                        return (
                            <tr key={nop} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                                <td className="px-2 py-1 border w-1">
                                    <Checkbox 
                                        checked={checkedItems.includes(nop)}
                                        onCheckedChange={() => handleSelectOne(nop)}
                                    />
                                </td>
                                <td className="px-2 py-1 border w-1">{value.kelurahan}</td>
                                <td className="px-2 py-1 border w-1">{value.blok}</td>
                                <td className="px-2 py-1 border w-1">{value.no}</td>
                                <td className="px-2 py-1 border w-1">{value.jenis}</td>
                                <td className="px-2 py-1 border">{value.nama}</td>
                                <td className="px-2 py-1 border">{value.alamat}</td>
                                <td className="px-2 py-1 border text-end">{value.pajak}</td>
                                <td className="px-2 py-1 border">{value.keterangan}</td>
                                <td className="px-2 py-1 border text-center">
                                <a
                                    className="p-1 text-[12px] bg-blue-500 text-white m-0 rounded hover:bg-blue-600 cursor-pointer flex items-center justify-center"
                                    target='_blank'
                                    href={route('transaksi.penyampaian.pengembalian.cetak-ba', value.id + '.' + data.tahun)}
                                >
                                    <Printer size={14} />
                                </a>
                                </td>
                            </tr>
                        );
                    })
                ) : (!loading ? (
                    <tr>
                        <td colSpan={10}>
                            <div className="flex items-center justify-center">
                                <DatabaseBackup size={18} className="me-2" /> Data tidak ditemukan
                            </div>
                        </td>
                    </tr>
                ) : null)}
            </tbody>
        </table>
        </>
    );
}
