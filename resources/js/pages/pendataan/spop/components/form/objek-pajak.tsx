import Combobox from "@/components/combobox";
import FormInput from "@/components/form-input";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Loader2, Search, TriangleAlert } from "lucide-react";
import LayerGoogleMap from "./layer-google-map";
import Nop from "./nop";
import { alertApp } from "@/components/utils";
import axios from "axios";
import { useEffect, useState } from "react";
type props = {
     data: any
     setData: React.Dispatch<React.SetStateAction<any>>
     formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
     errors: any
     jenisOptions: { value: string; label: string }[]
     tanahOptions: { value: string; label: string }[]
     isEdit: boolean
}
export default function ObjekPajak({data, setData, formRefs, errors, jenisOptions, tanahOptions, isEdit} : props) {
     const [loading, setLoading] = useState(false)
     const [dataNopTerbesar, setDataNopTerbesar] = useState({
          no_urut : '',
          selanjutnya : '',
     })
     const handleGetNOPTerbesar = async () => {
          if (!data.kd_blok) {
               if (formRefs?.current?.['kd_blok']) {
                   formRefs.current['kd_blok'].select();
               }
               return alertApp("Masukan kd blok dulu", 'error');
          }
          setLoading(true)
          try {
               const response = await axios.post(route('pendataan.spop.nop-terbesar'), data);
               setDataNopTerbesar({
                    no_urut : response.data.no_urut,
                    selanjutnya : response.data.selanjutnya,
               })
          } catch (error:any) {
               alertApp(error.response.data.message, 'error');
          }finally{
               setLoading(false)
          }
     };
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
                              isEdit={isEdit}
                         />
                    </div>
                    {data.nop && <div className='bg-red-500 rounded mb-3 mt-3 p-2 text-sm text-white'><TriangleAlert className="inline" size={18}/> PERINGATAN <br /> NOP Sudah digunakan.</div>}

                    {!isEdit && <div className="grid gap-2 md:grid-cols-3 mt-2 mb-2">
                            <Button type="button" className="w-full" onClick={handleGetNOPTerbesar} disabled={loading}> {loading ? <Loader2 className="animate-spin" /> : <Search /> } CEK NOP TERBESAR</Button>
                         <div className="md:col-span-2 bg-gray-100 p-2 rounded-md text-sm text-gray-700">
                              {dataNopTerbesar.no_urut && dataNopTerbesar.selanjutnya  && <table>
                                   <tbody>
                                        <tr>
                                             <td className="w-fit font-bold">🔎 NOP TERAKHIR</td>
                                             <td className="w-1">:</td>
                                             <td>{dataNopTerbesar.no_urut}</td>
                                        </tr>
                                        <tr>
                                             <td className="w-fit font-bold">✅ NOP BERIKUTNYA</td>
                                             <td className="w-1">:</td>
                                             <td>{dataNopTerbesar.selanjutnya}</td>
                                        </tr>
                                   </tbody>
                              </table>}
                              {!dataNopTerbesar.no_urut && !dataNopTerbesar.selanjutnya  && <p className="text-gray-500 italic">Klik tombol "CEK NOP TERBESAR" untuk melihat NOP terakhir.</p>}
                         </div>
                    </div>}
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
                    <div className="grid gap-2 md:grid-cols-4 mt-2 mb-2">
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
                         <div className="gap-2">
                              <Label>Bangunan</Label>
                              <Select defaultValue="tidak" onValueChange={(value) => {setData((prev:any) => ({...prev,bangunan:value}))}}>
                                   <SelectTrigger className="cursor-pointer">
                                        <SelectValue placeholder="Ada Bangunan?"/>
                                   </SelectTrigger>
                                   <SelectContent align="end">
                                        <SelectItem className='cursor-pointer' value="tidak">TIDAK</SelectItem>
                                        <SelectItem className='cursor-pointer' value="ya">YA</SelectItem>
                                   </SelectContent>
                              </Select>
                         </div>
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
