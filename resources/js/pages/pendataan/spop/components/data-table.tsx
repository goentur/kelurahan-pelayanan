import LoadingData from '@/components/data-table/loading-data'
import NoData from '@/components/data-table/no-data'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Gate } from '@/types'
import { BadgeX, Ellipsis, Eye, Pencil } from 'lucide-react'

type DataTableProps = {
    gate: Gate
    loading: boolean
    data: []
    from: number
    setForm: React.Dispatch<React.SetStateAction<boolean>>
    setFormDetail: React.Dispatch<React.SetStateAction<boolean>>
    setIsEdit: React.Dispatch<React.SetStateAction<boolean>>
    setData: React.Dispatch<React.SetStateAction<any>>
    setDataDetail: React.Dispatch<React.SetStateAction<any>>
    setHapus: React.Dispatch<React.SetStateAction<boolean>>
}

export default function DataTable({
    gate,
    loading,
    data,
    from,
    setForm,
    setFormDetail,
    setIsEdit,
    setData,
    setDataDetail,
    setHapus,
}: DataTableProps) {
    return (
        <table className="w-full text-left border-collapse border">
            <thead>
                <tr className="text-sm leading-normal">
                    <th className="p-2 border w-1">NO</th>
                    <th className="p-2 border w-1">NOP</th>
                    <th className="p-2 border hidden md:table-cell">ALAMAT</th>
                    <th className="p-2 border hidden md:table-cell">NAMA</th>
                    <th className="p-2 border hidden md:table-cell">TANAH m²</th>
                    <th className="p-2 border w-1">Aksi</th>
                </tr>
            </thead>
            <tbody className="font-light text-sm">
                {loading && <LoadingData colSpan={5}/>}
                {data.length > 0 ? (
                    data.map((value: any, index: number) => (
                        <tr
                            key={index}
                            className="hover:bg-gray-100 dark:hover:bg-slate-900 align-text-top"
                        >
                            <td className="px-2 py-1 border align-text-top text-center">{from++}</td>
                            <td className="px-2 py-1 border align-text-top">
                                <div className="font-medium">{value.nop}</div>
                                <div className="text-sm block md:hidden">{value.alamat}</div>
                                <div className="block md:hidden">{value.nama}</div>
                            </td>
                            <td className="px-2 py-1 border align-text-top hidden md:table-cell">{value.alamat}</td>
                            <td className="px-2 py-1 border align-text-top hidden md:table-cell">{value.nama}</td>
                            <td className="px-2 py-1 border align-text-top hidden md:table-cell">{value.tanah}</td>
                            <td className="border text-center">
                                <DropdownMenu>
                                    <DropdownMenuTrigger className='px-2 py-1 cursor-pointer'><Ellipsis/></DropdownMenuTrigger>
                                    <DropdownMenuContent align='end'>
                                        {/* {gate.update && <DropdownMenuItem onClick={() => {setForm(true), setIsEdit(true), setData({ id:value.id, nama:value.nama, jenis:value.jenis})}}><Pencil/> Ubah</DropdownMenuItem>} */}
                                        {gate.delete && <DropdownMenuItem onClick={() => {setHapus(true), setData({id:value.id,})}}><BadgeX/> Hapus</DropdownMenuItem>}
                                        <DropdownMenuItem onClick={() => {setFormDetail(true), setDataDetail(value)}}><Eye/> Detail</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </td>
                        </tr>
                    ))
                ) : (!loading ?<NoData colSpan={5}/>: null)}
            </tbody>
        </table>
    )
}
