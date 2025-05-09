import LoadingData from '@/components/data-table/loading-data'
import NoData from '@/components/data-table/no-data'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { Gate } from '@/types'
import { BadgeX, Ellipsis, Pencil } from 'lucide-react'

type DataTableProps = {
    gate: Gate
    loading: boolean
    data: []
    from: number
    setForm: React.Dispatch<React.SetStateAction<boolean>>
    setIsEdit: React.Dispatch<React.SetStateAction<boolean>>
    setData: React.Dispatch<React.SetStateAction<any>>
    setHapus: React.Dispatch<React.SetStateAction<boolean>>
}

export default function DataTable({
    gate,
    loading,
    data,
    from,
    setForm,
    setIsEdit,
    setData,
    setHapus,
}: DataTableProps) {
    return (
        <table className="w-full text-left border-collapse border">
            <thead>
                <tr className="uppercase text-sm leading-normal">
                    <th className="p-2 border w-1">NO</th>
                    <th className="p-2 border w-1">Email</th>
                    <th className="p-2 border">Nama</th>
                    <th className="p-2 border">Role</th>
                    <th className="p-2 border w-1">Aksi</th>
                </tr>
            </thead>
            <tbody className="font-light">
                {loading && <LoadingData colSpan={5}/>}
                {data.length > 0 ? (
                    data.map((value: any, index: number) => (
                        <tr
                            key={index}
                            className="hover:bg-gray-100 dark:hover:bg-slate-900 align-text-top"
                        >
                            <td className="px-2 py-1 border text-center">{from++}</td>
                            <td className="px-2 py-1 border">{value.email}</td>
                            <td className="px-2 py-1 border">{value.nama}</td>
                            <td className="px-2 py-1 border">{value.role}</td>
                            <td className="border text-center">
                                <DropdownMenu>
                                    <DropdownMenuTrigger className='px-2 py-1 cursor-pointer'><Ellipsis/></DropdownMenuTrigger>
                                    <DropdownMenuContent align='end'>
                                        {gate.update && <DropdownMenuItem onClick={() => {setForm(true), setIsEdit(true), setData({ id:value.id, email:value.email, nama:value.nama, role:value.role})}}><Pencil/> Ubah</DropdownMenuItem>}
                                        {gate.delete && <DropdownMenuItem onClick={() => {setHapus(true), setData({id:value.id,})}}><BadgeX/> Hapus</DropdownMenuItem>}
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
