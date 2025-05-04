import FormInput from '@/components/form-input'
import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog'
import { alertApp } from '@/components/utils';
import axios from 'axios';
import { Loader2, Save } from 'lucide-react'
import { useState } from 'react';

type TidakTersampaikan = {
  id: string;
  value: string;
  nama_wp: string;
  alamat_objek: string;
  nominal: string;
};

interface props {
    open: boolean
    setOpen: (value: boolean) => void
    dataYangTidakTersampaikan: TidakTersampaikan | null
    handleTidakTersampaikan: (id:any, status: boolean, value: string,message : string, keterangan : string) => void
}
export default function DialogKeterangan({
    open,
    setOpen,
    dataYangTidakTersampaikan,
    handleTidakTersampaikan,
}: props) {
    const [loading, setLoading] = useState(false);
    const [keterangan, setKeterangan] = useState<string>("");

    const handleForm = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        try {
            const response = await axios.post(route('transaksi.penyampaian.tidak-tersampaikan'), {
                id: dataYangTidakTersampaikan?.id,
                value: dataYangTidakTersampaikan?.value,
                keterangan : keterangan,
                nama_wp: dataYangTidakTersampaikan?.nama_wp,
                alamat_objek: dataYangTidakTersampaikan?.alamat_objek,
                nominal: dataYangTidakTersampaikan?.nominal,
            });
            handleTidakTersampaikan(dataYangTidakTersampaikan?.id, response.data?.status, response.data?.value, response.data?.message, keterangan);
            setKeterangan("")
        } catch (error: any) {
            alertApp(error.message, "error");
        } finally {
            setLoading(false);
        }
    };

    return (
        <Dialog open={open} onOpenChange={(val) => {if (!loading) {setOpen(val);}}}>
            <DialogContent className='w-xl'>
                <form onSubmit={handleForm}>
                    <DialogHeader>
                        <DialogTitle>Keterangan Objek Pajak</DialogTitle>
                        <DialogDescription className='italic'>"Silakan masukan keterangan berdasarkan Objek Pajak yang tidak tersampaikan"</DialogDescription>
                    </DialogHeader>
                    <div className="space-y-6 mt-5">
                        <FormInput
                            id="keterangan"
                            type="text"
                            value={keterangan}
                            onChange={(e) => setKeterangan(e.target.value)}
                            placeholder="Masukkan keterangan"
                            minLength={10}
                            autoFocus
                            required
                        />
                    </div>
                    <DialogFooter>
                        <div className="flex items-center mt-5">
                            <Button type="submit" disabled={loading}> 
                                {loading ? (<Loader2 className="animate-spin" />) : (<Save /> )} 
                                Simpan
                            </Button>
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
