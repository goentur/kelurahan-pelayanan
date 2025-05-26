import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog'
import { CheckCheck, Loader2, X } from 'lucide-react'
interface props {
    judul: string
    deskripsi: string
    open: boolean
    setOpen: (value: boolean) => void
    processing: boolean
    handleSubmit: (e: React.FormEvent) => void
}
export default function DialogKonfirmasi({
    judul,
    deskripsi,
    open,
    setOpen,
    processing,
    handleSubmit,
}: props) {
    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent className="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle className="text-xl">
                        {judul}
                    </DialogTitle>
                    <DialogDescription className="text-justify">
                        {deskripsi}
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter className="sm:justify-start gap-4">
                    <Button type="button" onClick={() => {setOpen(false)}}> <X /> Tidak</Button>
                    <Button type="button" variant="destructive" onClick={handleSubmit} disabled={processing}>
                        {processing ? (<Loader2 className="animate-spin" />) : (<CheckCheck />)} Ya
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    )
}
