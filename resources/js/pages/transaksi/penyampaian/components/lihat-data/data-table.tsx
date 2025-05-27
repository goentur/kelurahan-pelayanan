import { DatabaseBackup, Loader2 } from 'lucide-react';

type DataTableProps = {
    dataTable: any[];
    loading: boolean;
};

export default function DataTable({ dataTable,loading }: DataTableProps) {
    return (
        <table className="w-full text-left border-collapse border">
            <thead className="text-center text-sm">
                <tr className="uppercase leading-normal">
                    <th colSpan={4} className="px-2 border w-1">NOP</th>
                    <th className="px-2 border w-1/6">Nama</th>
                    <th className="px-2 border">Alamat</th>
                    <th className="px-2 border w-1">Pajak</th>
                    <th className="px-2 border w-1/5">Keterangan</th>
                </tr>
            </thead>
            <tbody className="font-light text-xs">
            {loading && (
                <tr>
                    <td className='p-1' colSpan={8}>
                        <div className="flex items-center justify-center">
                            <Loader2 className="animate-spin me-2" size={18} />Mohon Tunggu...
                        </div>
                    </td>
                </tr>
            )}
            {dataTable.length > 0 ? (
                dataTable.map((value: any, index: number) => (
                <tr key={index} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                    <td className="px-2 py-1 border w-1">{value.kelurahan}</td>
                    <td className="px-2 py-1 border w-1">{value.blok}</td>
                    <td className="px-2 py-1 border w-1">{value.no}</td>
                    <td className="px-2 py-1 border w-1">{value.jenis}</td>
                    <td className="px-2 py-1 border">{value.nama}</td>
                    <td className="px-2 py-1 border">{value.alamat}</td>
                    <td className="px-2 py-1 border text-end">{value.pajak}</td>
                    <td className="px-2 py-1 border">{value.keterangan}</td>
                </tr>
                ))
            ) : ( !loading ? 
                <tr>
                    <td colSpan={8}>
                        <div className="flex items-center justify-center">
                        <DatabaseBackup size={18} className="me-2" /> Data tidak ditemukan
                        </div>
                    </td>
                </tr>
             : null)}
            </tbody>
        </table>
    );
}
