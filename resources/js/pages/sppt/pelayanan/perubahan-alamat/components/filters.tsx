import PerPageSelect from '@/components/data-table/per-page-select'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Gate } from '@/types'
import { Loader2, Plus, Search } from 'lucide-react'
type filterProps = {
     gate: Gate
     tambah: () => void
     formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
     handleCari: (e: React.FormEvent) => void
     setData: React.Dispatch<React.SetStateAction<any>>
     data: any
     setInfoDataTabel: React.Dispatch<React.SetStateAction<any>>
     loading: boolean
};
const filterTipe = [
    {
        label : 'SEMUA',
        class : 'bg-cyan-500 hover:bg-cyan-600',
    },
    {
        label : 'MENUNGGU',
        class : 'bg-blue-500 hover:bg-blue-600',
    },
    {
        label : 'SELESAI',
        class : 'bg-green-500 hover:bg-green-600',
    },
    {
        label : 'TOLAK',
        class : 'bg-red-500 hover:bg-red-600',
    },
]
export default function Filters({gate, tambah, formRefs, handleCari, setData, data, setInfoDataTabel, loading}:filterProps) {
    return (
     <>
          <div className="mb-1 flex items-center justify-between flex-wrap gap-4">
               <div className="flex flex-col gap-2">
                    <PerPageSelect onChange={(value) => setInfoDataTabel((prev:any) => ({...prev,page: 1,perPage: value}))}/>
               </div>
               <form onSubmit={handleCari} className="flex gap-2 min-w-2/4">
                    <Select onValueChange={(value) => {setData((prev:any) => ({...prev,berdasarkan:value, search:""})), formRefs.current.search?.select()}}>
                         <SelectTrigger className="w-1/2 cursor-pointer">
                         <SelectValue placeholder="Filter berdasarkan"/>
                         </SelectTrigger>
                         <SelectContent align="end">
                              <SelectItem className='cursor-pointer' value="Nomor">Nomor</SelectItem>
                              <SelectItem className='cursor-pointer' value="NOP">NOP</SelectItem>
                              <SelectItem className='cursor-pointer' value="Nama">Nama Wajib Pajak</SelectItem>
                         </SelectContent>
                    </Select>
                    <Input
                         id={"cari"}
                         disabled={data.berdasarkan==""?true:false}
                         className="block w-full"
                         onChange={(e) => setData((prev:any) => ({...prev,search:e.target.value}))}
                         placeholder="Masukan kata pencarian"
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
          <hr className='my-2' />
          <div className="mb-2 flex items-center justify-between flex-wrap gap-4">
               <div className="flex gap-1">
                    {filterTipe.map((item: any, index: number) => (
                    <button
                         type="button"
                         onClick={() => {setInfoDataTabel((prev:any) => ({...prev,page: 1, status:item.label}))}}
                         disabled={loading}
                         key={index}
                         className={`${item.class} p-1 text-xs text-white rounded cursor-pointer disabled:cursor-not-allowed`}
                    >
                         {item.label}
                    </button>
                    ))}
               </div>
          </div>
     </>
    );
}
