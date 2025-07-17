import PerPageSelect from '@/components/data-table/per-page-select'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Gate } from '@/types'
import { Link } from '@inertiajs/react'
import { Download, Loader2, Plus, Search, Sheet } from 'lucide-react'

type filterProps = {
    gate: Gate
    tambah: () => void
    formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
    handleCari: (e: React.FormEvent) => void
    infoDataTabel: any
    setInfoDataTabel: React.Dispatch<React.SetStateAction<any>>
    loading: boolean
};
export default function DataTableFilters({gate, tambah, formRefs, handleCari, infoDataTabel, setInfoDataTabel, loading}: filterProps) {
    return (
        <div className="mb-1 flex justify-between items-center flex-wrap gap-4">
            <div className="flex gap-2">
                <PerPageSelect onChange={(value) =>setInfoDataTabel((prev:any) => ({...prev,page: 1,perPage: value}))}/>
                <Button asChild>
                    <a href={route('pendataan.spop.unduh-hasil-pendataan-per-user')} target="_blank">
                        <Download /> Unduh Semua Data
                    </a>
                </Button>
            </div>
            <form onSubmit={handleCari} className="flex gap-2 min-w-2/4">
                <Select onValueChange={(value) => {setInfoDataTabel((prev:any) => ({...prev,berdasarkan:value, search:""})), formRefs.current.search?.select()}}>
                    <SelectTrigger className="w-1/2 cursor-pointer">
                        <SelectValue placeholder="Filter"/>
                    </SelectTrigger>
                    <SelectContent align="end">
                        <SelectItem className='cursor-pointer' value="NOP">NOP</SelectItem>
                        <SelectItem className='cursor-pointer' value="Nama">Nama</SelectItem>
                    </SelectContent>
                </Select>
                <Input
                    id={"cari"}
                    disabled={infoDataTabel.berdasarkan==""?true:false}
                    className="block w-full"
                    onChange={(e) => setInfoDataTabel((prev:any) => ({...prev,search:e.target.value}))}
                    placeholder="Cari"
                    value={infoDataTabel.search}
                    ref={(el) => {if (formRefs.current) {formRefs.current['search'] = el}}}
                />
                <Button type="submit" disabled={loading}>
                    {loading ? <Loader2 className="animate-spin" /> : <Search/>} Cari
                </Button>
                {gate.create && 
                    <Button type="button" variant={"destructive"} onClick={tambah}>
                        <Plus/> Tambah
                    </Button>
                }
            </form>
        </div>
    )
}