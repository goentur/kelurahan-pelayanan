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
    const setEditData = (value:any) => setData({
        id: value.id,
        jenis: value.data.objek.jenis,
        kd_propinsi: value.data.objek.kd_propinsi,
        kd_dati2: value.data.objek.kd_dati2,
        kd_kecamatan: value.data.objek.kd_kecamatan,
        kd_kelurahan: value.data.objek.kd_kelurahan,
        kd_blok: value.data.objek.kd_blok,
        no_urut: value.data.objek.no_urut,
        kd_jns_op: value.data.objek.kd_jns_op,
        nop: false,
        jalan: value.data.objek.jalan,
        blok_kav_no: value.data.objek.blok_kav_no,
        rw: value.data.objek.rw,
        rt: value.data.objek.rt,
        luas_tanah: value.data.tanah.luas_tanah,
        no_sertipikat: value.data.tanah.no_sertipikat,
        tanah: value.data.tanah.tanah,
        keterangan: value.data.objek.keterangan,
        koordinat: value.data.objek.koordinat,
        status: value.data.subjek.status,
        pekerjaan: value.data.subjek.pekerjaan,
        nik: value.data.subjek.nik,
        npwp: value.data.subjek.npwp,
        nama: value.data.subjek.nama,
        jalan_sp: value.data.subjek.jalan,
        blok_kav_no_sp: value.data.subjek.blok_kav_no,
        rw_sp: value.data.subjek.rw,
        rt_sp: value.data.subjek.rt,
        kelurahan: value.data.subjek.kelurahan,
        kecamatan: value.data.subjek.kecamatan,
        kota: value.data.subjek.kota,
        kode_pos: value.data.subjek.kode_pos,
        no_telp: value.data.subjek.no_telp,
        email: value.data.subjek.email,
    });
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
                                        {gate.update && <DropdownMenuItem onClick={() => {setForm(true), setIsEdit(true), setEditData(value)}}><Pencil/> Ubah</DropdownMenuItem>}
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
