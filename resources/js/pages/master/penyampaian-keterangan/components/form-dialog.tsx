import Combobox from '@/components/combobox'
import FormInput from '@/components/form-input'
import InfoPassword from '@/components/info-password'
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
                                error={errors.name}
                                required
                            />
                            <FormInput
                                id="keterangan"
                                type="text"
                                value={data.keterangan}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, keterangan: e.target.value }))}
                                inputRef={(el) => {
                                    if (formRefs.current) {
                                        formRefs.current['keterangan'] = el;
                                    }
                                }}
                                placeholder="Masukkan keterangan"
                                error={errors.name}
                                required
                            />
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
