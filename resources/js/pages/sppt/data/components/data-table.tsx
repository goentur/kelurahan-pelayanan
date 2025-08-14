import LoadingData from "@/components/data-table/loading-data";
import NoData from "@/components/data-table/no-data";
import { Button } from "@/components/ui/button";
import { Info } from 'lucide-react';

type DataTableProps = {
    dataTable: any[];
    loading: boolean;
    infoOnClick: (id:any) => void;
};
export default function DataTable({ dataTable, loading, infoOnClick }: DataTableProps) {
    return (
        <table className="w-full text-left border-collapse border">
            <thead className="text-center text-sm">
                <tr className="uppercase leading-normal">
                    <th colSpan={5} className="px-2 border w-1">NOP</th>
                    <th className="px-2 border">Nama</th>
                    <th className="px-2 border">Alamat</th>
                    <th className="px-2 border w-1">Pajak</th>
                    <th className="px-2 border w-1">Bayar?</th>
                    <th className="px-2 border w-1">Aksi</th>
                </tr>
            </thead>
            <tbody className="font-light text-xs">
            {loading && <LoadingData colSpan={10}/>}
            {dataTable.length > 0 ? (
                dataTable.map((value: any, index: number) => (
                <tr key={index} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                    <td className="px-0.5 font-medium py-1 border w-1">{value.kecamatan}</td>
                    <td className="px-0.5 font-medium py-1 border w-1">{value.kelurahan}</td>
                    <td className="px-0.5 font-medium py-1 border w-1">{value.blok}</td>
                    <td className="px-0.5 font-medium py-1 border w-1">{value.no}</td>
                    <td className="px-0.5 font-medium py-1 border w-1">{value.jenis}</td>
                    <td className="px-2 py-1 border">
                        <p className='font-bold'>{value.nama}</p>
                        <p className='text-[10px]'>{value.alamat_wp}</p>
                    </td>
                    <td className="px-2 py-1 border">{value.alamat_op}</td>
                    <td className="px-2 py-1 border text-end">{value.pajak}</td>
                    <td className="px-2 py-1 border">
                        <span className={`px-1 rounded text-white ${value.status.status?'bg-green-500':'bg-red-500'}`}>{value.status.text}</span> 
                    </td>
                    <td className="px-2 py-1 border">
                        <Button size="icon" onClick={() => infoOnClick(value.id)}>
                            <Info />
                        </Button>
                    </td>
                </tr>
                ))
            ) : (!loading ?<NoData colSpan={10}/>: null)}
            </tbody>
        </table>
    );
}
