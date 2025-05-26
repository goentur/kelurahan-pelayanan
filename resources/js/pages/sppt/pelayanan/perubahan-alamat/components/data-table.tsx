import LoadingData from "@/components/data-table/loading-data";
import NoData from "@/components/data-table/no-data";
import StatusBadge from "@/components/status-badge";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Gate } from "@/types";
import { BadgeX, Check, Ellipsis, Pencil } from "lucide-react";

type DataTableProps = {
    gate: Gate;
    dataTable: any[];
    loading: boolean;
    setData: React.Dispatch<React.SetStateAction<any>>
    setDialogHapus: React.Dispatch<React.SetStateAction<boolean>>
    setDialogTerima: React.Dispatch<React.SetStateAction<boolean>>
    setDialogTolak: React.Dispatch<React.SetStateAction<boolean>>
};
export default function DataTable({ gate, dataTable, loading, setData, setDialogHapus, setDialogTerima, setDialogTolak }: DataTableProps) {
    return (
        <table className="w-full text-left border-collapse border">
            <thead className="text-center text-sm">
                <tr className="uppercase leading-normal">
                    <th className="px-2 border w-1">NOMOR/NOP</th>
                    <th className="px-2 border">NAMA WAJIB PAJAK</th>
                    <th className="px-2 border w-1">RW</th>
                    <th className="px-2 border w-1">RT</th>
                    <th className="px-2 border">JALAN</th>
                    <th className="px-2 border w-1">BLOK/KAV/NO</th>
                    <th className="px-2 border w-1">STATUS</th>
                    <th className="px-2 border w-1">#</th>
                </tr>
            </thead>
            <tbody className="font-light text-xs">
            {loading && <LoadingData colSpan={8}/>}
            {dataTable.length > 0 ? (
                dataTable.map((value: any, index: number) => (
                <tr key={index} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                    <td className="px-2 font-medium py-1 border">
                        <span className="font-bold text-sm">{value.nomor}</span>
                        <br />
                        <span className="font-light">{value.nop}</span>
                    </td>
                    <td className="px-2 font-medium py-1 border">
                    <span className="font-bold text-sm">{value.nama_wajib_pajak}</span>
                        <br />
                        <span className="font-light">{value.tanggal_permohonan}{value.tanggal_validasi?' / '+value.tanggal_validasi:''}</span>
                    </td>
                    <td className="px-2 font-medium py-1 border text-center">
                        <span className="font-bold">{value.rw_op_baru}</span>
                        <br />
                        <span className="font-light">{value.rw_op_lama}</span>
                    </td>
                    <td className="px-2 font-medium py-1 border text-center">
                        <span className="font-bold">{value.rt_op_baru}</span>
                        <br />
                        <span className="font-light">{value.rt_op_lama}</span>
                    </td>
                    <td className="px-2 font-medium py-1 border">
                        <span className="font-bold">{value.jalan_op_baru}</span>
                        <br />
                        <span className="font-light">{value.jalan_op_lama}</span>
                    </td>
                    <td className="px-2 font-medium py-1 border">
                        <span className="font-bold">{value.blok_kav_no_op_baru}</span>
                        <br />
                        <span className="font-light">{value.blok_kav_no_op_lama}</span>
                    </td>
                    <td className="px-0.5 font-medium py-1 border text-center"><StatusBadge value={value.status.value} color={value.status.color}/></td>
                    <td className="px-0.5 font-medium py-1 border">
                        {value.gate &&
                            <DropdownMenu>
                                <DropdownMenuTrigger className='cursor-pointer'><Ellipsis/></DropdownMenuTrigger>
                                <DropdownMenuContent align='end'>
                                    {gate.update&& <DropdownMenuItem><Pencil/> Ubah</DropdownMenuItem>}
                                    {gate.delete && <DropdownMenuItem onClick={() => {setDialogHapus(true), setData(value.id)}}><BadgeX/> Hapus</DropdownMenuItem>}
                                    {gate.validasi && <DropdownMenuItem onClick={() => {setDialogTerima(true), setData(value.id)}}><Check/> Terima</DropdownMenuItem>}
                                    {gate.validasi && <DropdownMenuItem onClick={() => {setDialogTolak(true), setData(value.id)}}><BadgeX/> Tolak</DropdownMenuItem>}
                                </DropdownMenuContent>
                            </DropdownMenu>
                        }
                    </td>
                </tr>
                ))
            ) : (!loading ?<NoData colSpan={8}/>: null)}
            </tbody>
        </table>
    );
}
