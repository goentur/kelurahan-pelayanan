import Combobox from '@/components/combobox'
import FormCalendar from '@/components/form-calendar'
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
    tipePenyampaian: { value: string; label: string }[]
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
    tipePenyampaian,
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
                                id="no_urut"
                                type="text"
                                value={data.no_urut}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, no_urut: e.target.value }))}
                                inputRef={(el) => {
                                    if (formRefs.current) {
                                        formRefs.current['no_urut'] = el;
                                    }
                                }}
                                placeholder="Masukkan no urut"
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
                            <Combobox label="jenis" selectedValue={data.jenis} options={tipePenyampaian} onSelect={(value) => setData((prevData:any) => ({ ...prevData, jenis: value }))} error={errors.jenis} />
                            <FormCalendar
                                id="tanggal_awal"
                                value={data.tanggal_awal}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, tanggal_awal: e }))}
                                inputRef={(el) => {
                                    if (formRefs.current) {
                                        formRefs.current['tanggal_awal'] = el;
                                    }
                                }}
                                placeholder="Pilih tanggal awal"
                                error={errors.tanggal_awal}
                                tanggalSelanjutnya={true}
                                required
                                autoComplete='off'
                            />
                            <FormCalendar
                                id="tanggal_akhir"
                                value={data.tanggal_akhir}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, tanggal_akhir: e }))}
                                inputRef={(el) => {
                                    if (formRefs.current) {
                                        formRefs.current['tanggal_akhir'] = el;
                                    }
                                }}
                                placeholder="Pilih tanggal akhir"
                                error={errors.tanggal_akhir}
                                tanggalSelanjutnya={true}
                                required
                                autoComplete='off'
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
