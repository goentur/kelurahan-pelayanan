import Combobox from '@/components/combobox'
import FormInput from '@/components/form-input'
import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog'
import { Loader2, Save } from 'lucide-react'
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from "@/components/ui/select";
import { Label } from '@/components/ui/label'
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    title: string
    data: any
    setData: (data: any) => void
    errors: any
    formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
    processing: boolean
    handleForm: (e: React.FormEvent) => void
    dataJabatan: { value: string; label: string }[];
}
export default function FormDialog({
    open,
    setOpen,
    title,
    data,
    setData,
    errors,
    formRefs,
    processing,
    handleForm,
    dataJabatan
}: props) {
    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent className='w-5xl'>
                <form onSubmit={handleForm}>
                    <DialogHeader>
                        <DialogTitle>Form {title}</DialogTitle>
                        <DialogDescription className='italic'>"Silakan isi formulir di bawah ini dengan lengkap dan benar"</DialogDescription>
                    </DialogHeader>
                    <div className="space-y-6 mt-5">
                        <div className="grid grid-cols-2 gap-4">
                            <FormInput
                                id="nik"
                                type="text"
                                value={data.nik}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, nik: e.target.value }))}
                                inputRef={(el) => {
                                    if (formRefs.current) {
                                        formRefs.current['nik'] = el;
                                    }
                                }}
                                placeholder="Masukkan nik"
                                error={errors.nik}
                                required
                            />
                            <FormInput
                                id="nip"
                                type="text"
                                value={data.nip}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, nip: e.target.value }))}
                                inputRef={(el) => {
                                    if (formRefs.current) {
                                        formRefs.current['nip'] = el;
                                    }
                                }}
                                placeholder="Masukkan nip"
                                error={errors.nip}
                            />
                            <FormInput
                                id="nama"
                                type="text"
                                value={data.nama}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, nama: e.target.value }))}
                                inputRef={(el) => {
                                    if (formRefs.current) {
                                        formRefs.current['nama'] = el;
                                    }
                                }}
                                placeholder="Masukkan nama"
                                error={errors.nama}
                                required
                            />
                            <FormInput
                                id="no_rekening"
                                type="text"
                                value={data.no_rekening}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, no_rekening: e.target.value }))}
                                inputRef={(el) => {
                                    if (formRefs.current) {
                                        formRefs.current['no_rekening'] = el;
                                    }
                                }}
                                placeholder="Masukkan no rekening"
                                error={errors.no_rekening}
                                required
                            />
                            <Combobox label="jabatan" selectedValue={data.jabatan} options={dataJabatan} onSelect={(value) => setData((prevData:any) => ({ ...prevData, jabatan: value }))} error={errors.jabatan} />
                                                
                            <div className="grid gap-2">
                                <Label htmlFor="jabatan_status" className="capitalize">
                                    Jabatan Status
                                </Label>
                                <Select defaultValue={data.jabatan_status} onValueChange={(value) => setData((prevData:any) => ({ ...prevData, jabatan_status: value }))}>
                                    <SelectTrigger className="cursor-pointer">
                                        <SelectValue placeholder="Pilih per jabatan status"/>
                                    </SelectTrigger>
                                    <SelectContent align="start">
                                        {['DEFINITIF', 'PLT', 'PLH'].map((num) => (
                                            <SelectItem key={num} value={num}>{num}</SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <div className="flex items-center mt-5">
                            <Button type="submit" disabled={processing}> {processing ? (<Loader2 className="animate-spin" />) : (<Save /> )} Simpan</Button>
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
