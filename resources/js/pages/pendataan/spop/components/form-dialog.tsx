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
import ObjekPajak from './form/objek-pajak'
import SubjekPajak from './form/subjek-pajak'
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
    tanahOptions: { value: string; label: string }[]
    statusOptions: { value: string; label: string }[]
    pekerjaanOptions: { value: string; label: string }[]
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
    tanahOptions,
    statusOptions,
    pekerjaanOptions,
}: props) {
    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent className='w-full max-h-[95vh] overflow-y-auto'>
                <form onSubmit={handleForm}>
                    <DialogHeader>
                        <DialogTitle>Form {title}</DialogTitle>
                        <DialogDescription className='italic'>"Silakan isi formulir di bawah ini dengan lengkap dan benar"</DialogDescription>
                    </DialogHeader>
                    <ObjekPajak
                        data={data}
                        setData={setData}
                        formRefs={formRefs}
                        errors={errors}
                        tanahOptions={tanahOptions}
                    />
                    <SubjekPajak
                        data={data}
                        setData={setData}
                        formRefs={formRefs}
                        errors={errors}
                        statusOptions={statusOptions}
                        pekerjaanOptions={pekerjaanOptions}
                    />
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
