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
import Bangunan from './form/bangunan'
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    title: string
    isEdit: boolean
    data: any
    setData: (data: any) => void
    errors: any
    formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
    processing: boolean
    handleForm: (e: React.FormEvent) => void
    jenisOptions: { value: string; label: string }[]
    tanahOptions: { value: string; label: string }[]
    statusOptions: { value: string; label: string }[]
    pekerjaanOptions: { value: string; label: string }[]
    jenisBangunanOptions: { value: string; label: string }[]
    kondisiOptions: { value: string; label: string }[]
    konstruksiOptions: { value: string; label: string }[]
    atapOptions: { value: string; label: string }[]
    dindingOptions: { value: string; label: string }[]
    lantaiOptions: { value: string; label: string }[]
    langitOptions: { value: string; label: string }[]
}
export default function FormDialog({
    open,
    setOpen,
    title,
    isEdit,
    data,
    setData,
    errors,
    formRefs,
    processing,
    handleForm,
    jenisOptions,
    tanahOptions,
    statusOptions,
    pekerjaanOptions,
    jenisBangunanOptions,
    kondisiOptions,
    konstruksiOptions,
    atapOptions,
    dindingOptions,
    lantaiOptions,
    langitOptions,
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
                        jenisOptions={jenisOptions}
                        tanahOptions={tanahOptions}
                        isEdit={isEdit}
                    />
                    <SubjekPajak
                        data={data}
                        setData={setData}
                        formRefs={formRefs}
                        errors={errors}
                        statusOptions={statusOptions}
                        pekerjaanOptions={pekerjaanOptions}
                    />
                    {!isEdit && data.bangunan=='ya' && 
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
                    }
                    <DialogFooter>
                        <div className="flex items-center mt-5">
                            <Button type="submit" disabled={processing || data.nop}> {processing ? (<Loader2 className="animate-spin" />) : (<Save /> )} Simpan</Button>
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}
