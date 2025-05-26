import Combobox from "@/components/combobox";
import FormInput from "@/components/form-input";
type props = {
     data: any
     setData: React.Dispatch<React.SetStateAction<any>>
     formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
     errors : any
     statusOptions: { value: string; label: string }[]
     pekerjaanOptions: { value: string; label: string }[]
 }
export default function SubjekPajak({data, setData, formRefs, errors, statusOptions, pekerjaanOptions} : props) {
     return (
     <div>
          <span className='text-2xl font-semibold'>SUBJEK PAJAK</span>
          <hr className='my-2' />
          <div className="grid gap-2 md:grid-cols-4 mt-2 mb-2">
               <Combobox label="status" selectedValue={data.status} options={statusOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, status: value }))} error={errors.status} />
               <Combobox label="pekerjaan" selectedValue={data.pekerjaan} options={pekerjaanOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, pekerjaan: value }))} error={errors.pekerjaan}/>
               <FormInput
                    id="NIK"
                    type="text"
                    value={data.nik}
                    onChange={(e) => setData((prevData: any) => ({ ...prevData, nik: e.target.value }))}
                    inputRef={(el) => {if (formRefs.current) {formRefs.current['nik'] = el;}}}
                    placeholder="nik"
                    error={errors.nik}
                    required
                    maxLength={16}
                    minLength={16}
               />
               <FormInput
                    id="NPWP"
                    type="text"
                    value={data.npwp}
                    onChange={(e) => setData((prevData: any) => ({ ...prevData, npwp: e.target.value }))}
                    inputRef={(el) => {if (formRefs.current) {formRefs.current['npwp'] = el;}}}
                    placeholder="npwp"
                    error={errors.npwp}
                    maxLength={16}
                    minLength={16}
               />
               <FormInput
                    id="nama"
                    type="text"
                    value={data.nama}
                    onChange={(e) => setData((prevData: any) => ({ ...prevData, nama: e.target.value }))}
                    inputRef={(el) => {if (formRefs.current) {formRefs.current['nama'] = el;}}}
                    placeholder="nama"
                    error={errors.nama}
                    required
               />
               <FormInput
                    id="jalan"
                    type="text"
                    value={data.jalan_sp}
                    onChange={(e) => setData((prevData: any) => ({ ...prevData, jalan_sp: e.target.value }))}
                    inputRef={(el) => {if (formRefs.current) {formRefs.current['jalan_sp'] = el;}}}
                    placeholder="jalan"
                    error={errors.jalan_sp}
                    required
               />
               <div className="grid gap-2 grid-cols-2">
                    <FormInput
                         id="blok_kav_no"
                         type="text"
                         value={data.blok_kav_no_sp}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, blok_kav_no_sp: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['blok_kav_no_sp'] = el;}}}
                         placeholder="Blok kav no"
                         error={errors.blok_kav_no_sp}
                    />
                    <div className="grid gap-2 grid-cols-2">
                         <FormInput
                              id="RW"
                              type="text"
                              value={data.rw_sp}
                              onChange={(e) => setData((prevData: any) => ({ ...prevData, rw_sp: e.target.value }))}
                              inputRef={(el) => {if (formRefs.current) {formRefs.current['rw_sp'] = el;}}}
                              placeholder="Rw"
                              error={errors.rw_sp}
                              required
                              maxLength={2}
                              minLength={2}
                         />
                         <FormInput
                              id="RT"
                              type="text"
                              value={data.rt_sp}
                              onChange={(e) => setData((prevData: any) => ({ ...prevData, rt_sp: e.target.value }))}
                              inputRef={(el) => {if (formRefs.current) {formRefs.current['rt_sp'] = el;}}}
                              placeholder="Rt"
                              error={errors.rt_sp}
                              required
                              maxLength={3}
                              minLength={3}
                         />
                    </div>
               </div>
               <FormInput
                    id="kelurahan"
                    type="text"
                    value={data.kelurahan}
                    onChange={(e) => setData((prevData: any) => ({ ...prevData, kelurahan: e.target.value }))}
                    inputRef={(el) => {if (formRefs.current) {formRefs.current['kelurahan'] = el;}}}
                    placeholder="kelurahan"
                    error={errors.kelurahan}
                    required
               />
               <FormInput
                    id="kecamatan"
                    type="text"
                    value={data.kecamatan}
                    onChange={(e) => setData((prevData: any) => ({ ...prevData, kecamatan: e.target.value }))}
                    inputRef={(el) => {if (formRefs.current) {formRefs.current['kecamatan'] = el;}}}
                    placeholder="kecamatan"
                    error={errors.kecamatan}
                    required
               />
               <FormInput
                    id="kota"
                    type="text"
                    value={data.kota}
                    onChange={(e) => setData((prevData: any) => ({ ...prevData, kota: e.target.value }))}
                    inputRef={(el) => {if (formRefs.current) {formRefs.current['kota'] = el;}}}
                    placeholder="kota"
                    error={errors.kota}
                    required
               />
               <div className="grid gap-2 grid-cols-2">
                    <FormInput
                         id="kode_pos"
                         type="text"
                         value={data.kode_pos}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, kode_pos: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['kode_pos'] = el;}}}
                         placeholder="kode pos"
                         error={errors.kode_pos}
                         maxLength={5}
                         minLength={5}
                    />
                    <FormInput
                         id="no_telp"
                         type="text"
                         value={data.no_telp}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, no_telp: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['no_telp'] = el;}}}
                         placeholder="no telp"
                         error={errors.no_telp}
                         required
                    />
               </div>
               <FormInput
                    id="email"
                    type="text"
                    value={data.email}
                    onChange={(e) => setData((prevData: any) => ({ ...prevData, email: e.target.value }))}
                    inputRef={(el) => {if (formRefs.current) {formRefs.current['email'] = el;}}}
                    placeholder="email"
                    error={errors.email}
               />
          </div>
     </div>
    )
}
