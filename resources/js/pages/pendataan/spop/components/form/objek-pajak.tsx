import Combobox from "@/components/combobox";
import FormInput from "@/components/form-input";
import InputError from "@/components/input-error";
import { Label } from "@/components/ui/label";
import LayerGoogleMap from "./layer-google-map";
import Nop from "./nop";
type props = {
     data: any
     setData: React.Dispatch<React.SetStateAction<any>>
     formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
     errors: any
     jenisOptions: { value: string; label: string }[]
     tanahOptions: { value: string; label: string }[]
 }
export default function ObjekPajak({data, setData, formRefs, errors, jenisOptions, tanahOptions} : props) {
     return (
     <div>
          <span className='text-2xl font-semibold'>OBJEK PAJAK</span>
          <hr className='my-2' />
          <div className="grid gap-2 md:grid-cols-2 mt-2 mb-2">
               <div>
                    <Combobox label="jenis" selectedValue={data.jenis} options={jenisOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, jenis: value }))} error={errors.jenis}/>
                    <div className="gap-2 md:w-3/4">
                         <Label>
                              NOP
                         </Label>
                         <Nop 
                              data={data}
                              setData={setData}
                              formRefs={formRefs}
                              errors={errors}
                              selanjutnya={"jalan"}
                         />
                    </div>
                    <div className="grid gap-2 md:grid-cols-2 mt-2 mb-2">
                         <FormInput
                              id="jalan"
                              type="text"
                              value={data.jalan}
                              onChange={(e) => setData((prevData: any) => ({ ...prevData, jalan: e.target.value }))}
                              inputRef={(el) => {if (formRefs.current) {formRefs.current['jalan'] = el;}}}
                              placeholder="Jalan"
                              error={errors.jalan}
                              required
                         />
                         <div className="grid gap-2 grid-cols-2">
                              <FormInput
                                   id="blok_kav_no"
                                   type="text"
                                   value={data.blok_kav_no}
                                   onChange={(e) => setData((prevData: any) => ({ ...prevData, blok_kav_no: e.target.value }))}
                                   inputRef={(el) => {if (formRefs.current) {formRefs.current['blok_kav_no'] = el;}}}
                                   placeholder="Blok kav no"
                                   error={errors.blok_kav_no}
                              />
                              <div className="grid gap-2 grid-cols-2">
                                   <FormInput
                                        id="RW"
                                        type="text"
                                        value={data.rw}
                                        onChange={(e) => setData((prevData: any) => ({ ...prevData, rw: e.target.value }))}
                                        inputRef={(el) => {if (formRefs.current) {formRefs.current['rw'] = el;}}}
                                        placeholder="Rw"
                                        error={errors.rw}
                                        required
                                        maxLength={2}
                                        minLength={2}
                                   />
                                   <FormInput
                                        id="RT"
                                        type="text"
                                        value={data.rt}
                                        onChange={(e) => setData((prevData: any) => ({ ...prevData, rt: e.target.value }))}
                                        inputRef={(el) => {if (formRefs.current) {formRefs.current['rt'] = el;}}}
                                        placeholder="Rt"
                                        error={errors.rt}
                                        required
                                        maxLength={3}
                                        minLength={3}
                                   />
                              </div>
                         </div>
                    </div>
                    <div className="grid gap-2 md:grid-cols-3 mt-2 mb-2">
                         <FormInput
                              id="luas_tanah"
                              type="text"
                              value={data.luas_tanah}
                              onChange={(e) => setData((prevData: any) => ({ ...prevData, luas_tanah: e.target.value }))}
                              inputRef={(el) => {if (formRefs.current) {formRefs.current['luas_tanah'] = el;}}}
                              placeholder="luas tanah"
                              error={errors.luas_tanah}
                              required
                              maxLength={10}
                              minLength={1}
                         />
                         <FormInput
                              id="no_sertipikat"
                              type="text"
                              value={data.no_sertipikat}
                              onChange={(e) => setData((prevData: any) => ({ ...prevData, no_sertipikat: e.target.value }))}
                              inputRef={(el) => {if (formRefs.current) {formRefs.current['no_sertipikat'] = el;}}}
                              placeholder="no sertipikat"
                              error={errors.no_sertipikat}
                              minLength={4}
                         />
                         <Combobox label="tanah" selectedValue={data.tanah} options={tanahOptions} onSelect={(value) => setData((prevData:any) => ({ ...prevData, tanah: value }))} error={errors.tanah}/>
                    </div>
                    <FormInput
                         id="Keterangan"
                         type="text"
                         value={data.keterangan}
                         onChange={(e) => setData((prevData: any) => ({ ...prevData, keterangan: e.target.value }))}
                         inputRef={(el) => {if (formRefs.current) {formRefs.current['keterangan'] = el;}}}
                         placeholder="Keterangan"
                         error={errors.keterangan}
                         required
                         maxLength={3}
                         minLength={3}
                    />
               </div>
               <div className='gap-2'>
                    <Label>
                         Lokasi Objek Pajak di Google Map
                    </Label>
                    <LayerGoogleMap data={data} setData={setData}/>
                    <InputError message={errors.koordinat} />
               </div>
          </div>
     </div>
    )
}
