import Combobox from '@/components/combobox'
import FormInput from '@/components/form-input'
import InfoPassword from '@/components/info-password'
import InputError from '@/components/input-error'
import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Loader2, Save } from 'lucide-react'
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    title: string
    data: any
    isEdit: boolean
    setData: (data: any) => void
    errors: any
    formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
    processing: boolean
    handleForm: (e: React.FormEvent) => void
    dataRoles: { value: string; label: string }[]
}
export default function FormDialog({
    open,
    setOpen,
    title,
    data,
    isEdit,
    setData,
    errors,
    formRefs,
    processing,
    handleForm,
    dataRoles,
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
                                id="email"
                                type="email"
                                value={data.email}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, email: e.target.value }))}
                                inputRef={(el) => {
                                    if (formRefs.current) {
                                        formRefs.current['email'] = el;
                                    }
                                }}
                                placeholder="Masukkan email"
                                error={errors.email}
                                readOnly={isEdit}
                                autoFocus
                                required
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
                            {!isEdit && (<>
                                <FormInput
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData((prevData: any) => ({ ...prevData, password: e.target.value }))}
                                    inputRef={(el) => {
                                        if (formRefs.current) {
                                            formRefs.current['password'] = el;
                                        }
                                    }}
                                    placeholder="Masukkan password"
                                    error={errors.password}
                                    required
                                />
                                <FormInput
                                    id="konfirmasi_password"
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData((prevData: any) => ({ ...prevData, password_confirmation: e.target.value }))}
                                    inputRef={(el) => {
                                        if (formRefs.current) {
                                            formRefs.current['password_confirmation'] = el;
                                        }
                                    }}
                                    placeholder="Konfirmasi password"
                                    required
                                />
                                <InfoPassword/>
                            </>)}
                            <Combobox label="role" selectedValue={data.role} options={dataRoles} onSelect={(value) => setData((prevData:any) => ({ ...prevData, role: value }))} error={errors.role} />
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
