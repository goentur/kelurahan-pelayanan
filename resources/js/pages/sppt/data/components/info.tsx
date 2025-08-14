import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog';
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { BookOpenText, DollarSign, MapPin } from 'lucide-react';
import Umum from './info/umum';
import Tagihan from './info/tagihan';
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    data: any
}
export default function Info({
    open,
    setOpen,
    data,
}: props) {
    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent className='w-7xl max-h-[95vh] overflow-y-auto'>
                <DialogHeader>
                    <DialogTitle>INFORMASI SPPT PBB-P2</DialogTitle>
                </DialogHeader>
                    <div className="flex items-center gap-2 text-primary text-3xl font-bold">
                    <MapPin className="w-7 h-7 text-primary" />
                    <span className="tracking-wide">{data.nop}</span>
                    </div>
                <Tabs defaultValue="umum">
                    <TabsList>
                        <TabsTrigger value="umum"><BookOpenText/> UMUM</TabsTrigger>
                        <TabsTrigger value="tagihan"><DollarSign/> TAGIHAN</TabsTrigger>
                    </TabsList>
                    <TabsContent value="umum"><Umum data={data}/></TabsContent>
                    <TabsContent value="tagihan"><Tagihan data={data}/> </TabsContent>
                </Tabs>
                <DialogFooter>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    )
}
