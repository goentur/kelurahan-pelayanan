import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { alertApp } from '@/components/utils'
import { useForm } from '@inertiajs/react'
import axios from 'axios'
import { Loader2, Plus, Save, Trash2 } from 'lucide-react'
import { useEffect, useRef, useState } from 'react'
import Bangunan from './form/bangunan'
import Delete from '@/components/data-table/delete'
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    title: string
    dataDetail: any
    data: any
    setData: (data: any) => void
    errors: any
    formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
    processing: boolean
    handleForm: (e: React.FormEvent) => void
    jenisBangunanOptions: { value: string; label: string }[]
    kondisiOptions: { value: string; label: string }[]
    konstruksiOptions: { value: string; label: string }[]
    atapOptions: { value: string; label: string }[]
    dindingOptions: { value: string; label: string }[]
    lantaiOptions: { value: string; label: string }[]
    langitOptions: { value: string; label: string }[]
}
export default function FormDialogDetail({
    open,
    setOpen,
    title,
    dataDetail,
    jenisBangunanOptions,
    kondisiOptions,
    konstruksiOptions,
    atapOptions,
    dindingOptions,
    lantaiOptions,
    langitOptions,
}: props) {
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const { data, setData, errors, post, delete: destroy, processing} = useForm();
    useEffect(() => {
        if (open && dataDetail?.id) {
            getData();
            reset();
        }
    }, [open, dataDetail?.id]);
    const [dataDetailSpop, setDataDetailSpop] = useState<[]>([])
    const [isAdd, setIsAdd] = useState(false)
    const getData = async () => {
        try {
            const response = await axios.post(route('pendataan.spop.data-detail'),{id:dataDetail.id});
            setDataDetailSpop(response.data)
            setData((prev) => ({
                ...prev,
                ...response.data[0],
                pendataan_spop: dataDetail.id
            }));
            setIsAdd(false)
        } catch (error:any) {
            alertApp(error.response.data.message, 'error');
        }
    };
    const reset = () => setData({
        id: '',
        pendataan_spop: dataDetail.id,
        jenis_bangunan: '',
        luas_bangunan: '',
        jumlah_lantai: '',
        tahun_dibangun: '',
        tahun_renovasi: '',
        daya_listrik: '',
        jumlah_ac: '',
        kondisi: '',
        konstruksi: '',
        atap: '',
        dinding: '',
        lantai: '',
        langit: '',
    });
    const handleForm = (e: React.FormEvent) => {
        e.preventDefault()
        const routeName = isAdd ? route('pendataan.spop.add-bangunan').toString() : route('pendataan.spop.update-bangunan', data).toString()
        post(routeName, {
            preserveScroll: true,
            onSuccess: (e) => {
                reset()
                alertApp(e)
                getData()
            },
            onError: (e) => {
                const firstErrorKey = Object.keys(e)[0]
                if (firstErrorKey) {
                    formRefs.current[firstErrorKey]?.focus()
                }
            },
        })
    }
    const [hapus, setHapus] = useState(false)
    const handleHapus = (e: React.FormEvent) => {
        e.preventDefault()
        destroy(route('pendataan.spop.delete-bangunan', data), {
            preserveScroll: true,
            onSuccess: (e) => {
                setHapus(false)
                alertApp(e)
                getData()
            },
            onError: (e) => {
                alertApp(e.message, 'error')
            },
        })
    }
    return (
        <>
            <Dialog open={open} onOpenChange={setOpen}>
                <DialogContent className='w-full max-h-[95vh] overflow-y-auto'>
                    <form onSubmit={handleForm}>
                        <DialogHeader>
                            <DialogTitle>Form Detail {title}</DialogTitle>
                            <DialogDescription className='italic'>"Silakan isi formulir di bawah ini dengan lengkap dan benar"</DialogDescription>
                        </DialogHeader>
                        <table className="table-auto border-collapse border w-full text-sm md:w-1/2">
                            <tbody>
                                <tr>
                                    <td className="border px-2 w-1 py-1 font-semibold">NOP</td>
                                    <td className="border px-2 py-1">{dataDetail.nop}</td>
                                </tr>
                                <tr>
                                    <td className="border px-2 py-1 font-semibold">Alamat</td>
                                    <td className="border px-2 py-1">{dataDetail.alamat}</td>
                                </tr>
                                <tr>
                                    <td className="border px-2 py-1 font-semibold">Nama</td>
                                    <td className="border px-2 py-1">{dataDetail.nama}</td>
                                </tr>
                                <tr>
                                    <td className="border px-2 py-1 font-semibold">Tanah</td>
                                    <td className="border px-2 py-1">{dataDetail.tanah} m²</td>
                                </tr>
                                <tr>
                                    <td className="border px-2 py-1 font-semibold">Bangunan</td>
                                    <td className="border px-2 py-1">
                                        <Select
                                            onValueChange={(value) => {
                                                const selected = dataDetailSpop.find((item: any) => item.id === parseInt(value));
                                                if (selected) {setData(selected)}
                                            }}
                                            value={String(data?.id || '')}
                                        >
                                            <SelectTrigger className="cursor-pointer">
                                                <SelectValue placeholder="bangunan ke"/>
                                            </SelectTrigger>
                                            <SelectContent align="end">
                                                {dataDetailSpop.map((value: any, index: number) => (
                                                    <SelectItem key={value.id} className='cursor-pointer' value={String(value.id)}>
                                                        {index + 1}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <Button type="button" className='my-2' onClick={()=> {setIsAdd(true);reset()}}> <Plus /> Bangunan Baru</Button>
                        <Button type="button" variant="destructive" className='my-2 ms-2' disabled={dataDetailSpop.length<2} onClick={()=> setHapus(true)}> <Trash2 /> Data Yang Dipilih</Button>
                        <Bangunan
                            data={data}
                            setData={setData}
                            formRefs={formRefs}
                            errors={errors}
                            jenisBangunanOptions={jenisBangunanOptions}
                            kondisiOptions={kondisiOptions}
                            konstruksiOptions={konstruksiOptions}
                            atapOptions={atapOptions}
                            dindingOptions={dindingOptions}
                            lantaiOptions={lantaiOptions}
                            langitOptions={langitOptions}
                        />
                        <DialogFooter>
                            <div className="flex items-center mt-5">
                                <Button type="submit" disabled={processing}> {processing ? <Loader2 className="animate-spin" /> : <Save />} Simpan</Button>
                            </div>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
            <Delete
                open={hapus}
                setOpen={setHapus}
                processing={processing}
                handleHapusData={handleHapus}
            />
        </>
    )
}
