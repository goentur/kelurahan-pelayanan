import Combobox from "@/components/combobox";
import FormInput from "@/components/form-input";
type props = {
     data: any
     setData: React.Dispatch<React.SetStateAction<any>>
     formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
     errors : any
     jenisBangunanOptions: { value: string; label: string }[]
     kondisiOptions: { value: string; label: string }[]
     konstruksiOptions: { value: string; label: string }[]
     atapOptions: { value: string; label: string }[]
     dindingOptions: { value: string; label: string }[]
     lantaiOptions: { value: string; label: string }[]
     langitOptions: { value: string; label: string }[]
 }
export default function Bangunan({data, setData, formRefs, errors, jenisBangunanOptions, kondisiOptions, konstruksiOptions, atapOptions, dindingOptions, lantaiOptions, langitOptions} : props) {
     return (
     <div>
          <span className='text-2xl font-semibold'>BANGUNAN</span>
          <hr className='my-2' />
          <div className="grid gap-2 md:grid-cols-4 mt-2 mb-2">
               <Combobox label="jenis_bangunan" selectedValue={data.jenis_bangunan} options={jenisBangunanOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, jenis_bangunan: value }))} error={errors.jenis_bangunan}/>   
               <div className="grid gap-2 md:grid-cols-2 mt-2 mb-2">
                    <FormInput
                         id="luas_bangunan"
                         type="text"
                         value={data.luas_bangunan}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, luas_bangunan: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['luasBangunan'] = el;}}}
                         placeholder="luas bangunan"
                         error={errors.luas_bangunan}
                         required
                    />
                    <FormInput
                         id="jumlah_lantai"
                         type="text"
                         value={data.jumlah_lantai}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, jumlah_lantai: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['jumlah_lantai'] = el;}}}
                         placeholder="jumlah lantai"
                         error={errors.jumlah_lantai}
                         required
                    />
               </div>
               <div className="grid gap-2 md:grid-cols-2 mt-2 mb-2">
                    <FormInput
                         id="tahun_dibangun"
                         type="text"
                         value={data.tahun_dibangun}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, tahun_dibangun: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['tahun_dibangun'] = el;}}}
                         placeholder="tahun dibangun"
                         error={errors.tahun_dibangun}
                         required
                         maxLength={4}
                         minLength={4}
                    />
                    <FormInput
                         id="tahun_renovasi"
                         type="text"
                         value={data.tahun_renovasi}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, tahun_renovasi: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['tahun_renovasi'] = el;}}}
                         placeholder="tahun renovasi"
                         error={errors.tahun_renovasi}
                         required
                         maxLength={4}
                         minLength={4}
                    />
               </div>
               <div className="grid gap-2 md:grid-cols-2 mt-2 mb-2">
                    <FormInput
                         id="daya_listrik"
                         type="text"
                         value={data.daya_listrik}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, daya_listrik: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['daya_listrik'] = el;}}}
                         placeholder="daya listrik"
                         error={errors.daya_listrik}
                         required
                    />
                    <FormInput
                         id="jumlah_ac"
                         type="text"
                         value={data.jumlah_ac}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, jumlah_ac: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['jumlah_ac'] = el;}}}
                         placeholder="jumlah AC"
                         error={errors.jumlah_ac}
                         required
                    />
               </div>
               <Combobox label="kondisi" selectedValue={data.kondisi} options={kondisiOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, kondisi: value }))} error={errors.kondisi}/>
               <Combobox label="konstruksi" selectedValue={data.konstruksi} options={konstruksiOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, konstruksi: value }))} error={errors.konstruksi}/>
               <Combobox label="atap" selectedValue={data.atap} options={atapOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, atap: value }))} error={errors.atap}/>
               <Combobox label="dinding" selectedValue={data.dinding} options={dindingOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, dinding: value }))} error={errors.dinding}/>
               <Combobox label="lantai" selectedValue={data.lantai} options={lantaiOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, lantai: value }))} error={errors.lantai}/>
               <Combobox label="langit" selectedValue={data.langit} options={langitOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, langit: value }))} error={errors.langit}/>
          </div>
     </div>
    )
}
