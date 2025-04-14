import LoadingData from '@/components/data-table/loading-data'
import NoData from '@/components/data-table/no-data'
import { Checkbox } from '@/components/ui/checkbox'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { alertApp } from '@/components/utils'
import { Gate } from '@/types'
import axios from 'axios'
import { BadgeX, Ellipsis, Pencil } from 'lucide-react'
import { useEffect, useState } from 'react'

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
    const [checkboxStates, setCheckboxStates] = useState<Record<string, boolean>>({});
    
    useEffect(() => {
        const initialState = data.reduce((acc, value:any) => {
            acc[value.id] = value.status;
            return acc;
        }, {} as Record<string, boolean>);
        
        setCheckboxStates(initialState);
    }, [data]);

    const handleCheckboxChange = (id: string) => {
        setCheckboxStates((prevState) => {
            const newState = !prevState[id];
            updateStatus(id, newState);
            return { ...prevState, [id]: newState };
        });
    };

    const updateStatus = async (id: string, newStatus: boolean) => {
        try {
            const respons =  await axios.post(route('master.pegawai.status'), { id, status: newStatus });
            alertApp(respons.data);
        } catch (error:any) {
            alertApp(error.message, 'error');
        }
    };
    return (
        <table className="w-full text-left border-collapse border">
            <thead>
                <tr className="uppercase text-sm leading-normal">
                    <th className="p-2 border w-1">NO</th>
                    <th className="p-2 border">Jabatan</th>
                    <th className="p-2 border w-1">NIK</th>
                    <th className="p-2 border w-1">NIP</th>
                    <th className="p-2 border">Nama</th>
                    <th className="p-2 border">No Rekening</th>
                    <th className="p-2 border w-1">Status</th>
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
                            <td className="px-2 py-1 border">{value.jabatan?.nama}</td>
                            <td className="px-2 py-1 border">{value.nik}</td>
                            <td className="px-2 py-1 border">{value.nip}</td>
                            <td className="px-2 py-1 border">{value.nama}</td>
                            <td className="px-2 py-1 border">{value.no_rekening}</td>
                            <td className="px-2 py-1 border text-center">
                                <Checkbox
                                    id={`checkbox-${value.id}`}
                                    checked={checkboxStates[value.id]}
                                    onCheckedChange={() => handleCheckboxChange(value.id)}
                                />
                            </td>
                            <td className="border text-center">
                                <DropdownMenu>
                                    <DropdownMenuTrigger className='px-2 py-1 cursor-pointer'><Ellipsis/></DropdownMenuTrigger>
                                    <DropdownMenuContent align='end'>
                                        {gate.update && <DropdownMenuItem onClick={() => {setForm(true), setIsEdit(true), setData({ id:value.id, jabatan:value.jabatan.id,nik:value.nik,nip:value.nip,nama:value.nama, no_rekening:value.no_rekening})}}><Pencil/> Ubah</DropdownMenuItem>}
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
