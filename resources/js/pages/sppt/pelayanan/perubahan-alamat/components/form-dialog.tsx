import Combobox from '@/components/combobox'
import FormInput from '@/components/form-input'
import { Button } from '@/components/ui/button'
import {
     Dialog,
     DialogContent,
     DialogDescription,
     DialogHeader,
     DialogTitle
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { alertApp } from '@/components/utils'
import axios from 'axios'
import { Files, Loader2, Save, Search } from 'lucide-react'
import { useState } from 'react'
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    title: string
    loadingSaveOrUpdate: boolean
    dataForm: any
    setDataForm: React.Dispatch<React.SetStateAction<any>>
    formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
    handleForm: (e: React.FormEvent) => void
    dataBerdasarkanUser: { value: string; label: string }[]
}
export default function FormDialog({
    open,
    setOpen,
    title,
    loadingSaveOrUpdate,
    dataForm,
    setDataForm,
    formRefs,
    handleForm,
    dataBerdasarkanUser,
}: props) {
    const [loading, setLoading] = useState(false);
    const getDataObjekPajak = async (e: React.FormEvent) => {
          e.preventDefault();
          setLoading(true)
          try {
               const response = await axios.post(route('sppt.pelayanan.perubahan-alamat.data-objek-pajak'),dataForm);
               setDataForm((prev: any) => ({
                    ...prev,
                    nama_wajib_pajak: response.data.nama_wajib_pajak,
                    rw_lama: response.data.rw_lama ?? '-',
                    rt_lama: response.data.rt_lama ?? '-',
                    jalan_lama: response.data.jalan_lama ?? '-',
                    blok_kav_no_lama: response.data.blok_kav_no_lama ?? '-',
                    rw_baru: '',
                    rt_baru: '',
                    jalan_baru: '',
                    blok_kav_no_baru: '',
               }));
               formRefs.current?.rw?.select();
          } catch (error:any) {
               alertApp(error.response.data.message, 'error');
          }finally{
               setLoading(false)
          }
     };
     const salinKeForm = (e: React.FormEvent) => {
          e.preventDefault();
          setDataForm((prev: any) => ({
               ...prev,
               rw_baru: dataForm.rw_lama ?? '-',
               rt_baru: dataForm.rt_lama ?? '-',
               jalan_baru: dataForm.jalan_lama ?? '-',
               blok_kav_no_baru: dataForm.blok_kav_no_lama ?? '-',
          }));
          formRefs.current?.rw?.select();
     }
    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogContent className='w-10/12'>
                    <DialogHeader>
                        <DialogTitle>Form {title}</DialogTitle>
                        <DialogDescription className='italic'>"Silakan isi formulir di bawah ini dengan lengkap dan benar"</DialogDescription>
                    </DialogHeader>
                    <form onSubmit={getDataObjekPajak} className="grid gap-2 lg:grid-cols-2 md:grid-cols-2">
                         <div>
                              <Combobox
                                   label="Kelurahan"
                                   selectedValue={dataForm.kelurahan}
                                   options={dataBerdasarkanUser}
                                   onSelect={(value) => {
                                        setDataForm((prev: any) => ({ ...prev, kelurahan: value }));
                                        formRefs.current.kd_blok?.select();
                                   }}
                              />
                         </div>
                         <div className='grid gap-2 lg:grid-cols-4 md:grid-cols-4 mb-3'>
                              <FormInput
                                   id="kd_blok"
                                   type="text"
                                   value={dataForm.kd_blok??""}
                                   onChange={(e) => {
                                        const value = e.target.value;
                                        setDataForm((prevData: any) => ({ ...prevData, kd_blok: value }));

                                        if (value.length === 3 && formRefs.current?.no_urut) {
                                             formRefs.current.no_urut.select();
                                        }
                                   }}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['kd_blok'] = el;
                                        }
                                   }}
                                   required
                                   placeholder="kd blok"
                                   maxLength={3}
                              />
                              <FormInput
                                   id="no_urut"
                                   type="text"
                                   value={dataForm.no_urut??""}
                                   onChange={(e) => {
                                        const value = e.target.value;
                                        setDataForm((prevData: any) => ({ ...prevData, no_urut: value }));

                                        if (value.length === 4 && formRefs.current?.kd_jenis) {
                                             formRefs.current.kd_jenis.select();
                                        }
                                   }}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['no_urut'] = el;
                                        }
                                   }}
                                   required
                                   placeholder="no urut"
                                   maxLength={4}
                              />
                              <FormInput
                                   id="kd_jenis"
                                   type="text"
                                   value={dataForm.kd_jns_op??""}
                                   onChange={(e) => {
                                        setDataForm((prevData: any) => ({ ...prevData, kd_jns_op: e.target.value }));
                                   }}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['kd_jenis'] = el;
                                        }
                                   }}
                                   required
                                   placeholder="kd jenis"
                                   maxLength={1}
                              />
                              <div>
                                   <Button type="submit" disabled={loading || loadingSaveOrUpdate} className='mt-5'>
                                        {loading ? <Loader2 className="animate-spin" /> : <Search/>} Cari
                                   </Button>
                              </div>
                         </div>
                    </form>
                    <hr className='mt-2' />
                    <form onSubmit={handleForm}>
                         <table className="w-full text-left">
                              <thead>
                                   {dataForm.rw_lama && <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                             <Button type='button' variant="destructive" className='mb-3' onClick={salinKeForm}><Files/> Salin ke form</Button>
                                        </td>
                                   </tr>}
                                   <tr>
                                        <th className='px-2'>#</th>
                                        <th className='px-2 w-1'></th>
                                        <th className='px-2 w-1/3'>ALAMAT OBJEK PAJAK LAMA</th>
                                        <th className='px-2 w-1/2'>ALAMAT OBJEK PAJAK BARU</th>
                                   </tr>
                              </thead>
                              <tbody>
                                   <tr>
                                        <td className='px-2'>NAMA WAJIB PAJAK</td>
                                        <td>:</td>
                                        <td>{dataForm.nama_wajib_pajak ? dataForm.nama_wajib_pajak : '-'}</td>                                       
                                        <td></td>
                                   </tr>
                                   <tr>
                                        <td className='px-2'>RW/RT</td>
                                        <td>:</td>
                                        <td>
                                             {dataForm.rw_lama ? dataForm.rw_lama : '00'}
                                             /
                                             {dataForm.rt_lama ? dataForm.rt_lama : '000'}
                                        </td>
                                        <td className='flex mb-3'>
                                             <Input
                                                  className='w-1/4'
                                                  type='number'
                                                  required
                                                  min={1}
                                                  maxLength={2}
                                                  minLength={2}
                                                  placeholder='Masukan RW'
                                                  value={dataForm.rw_baru??""}
                                                  onChange={(e) => {
                                                       const value = e.target.value;
                                                       setDataForm((prevData: any) => ({ ...prevData, rw_baru: value }));
                                                       if (value.length === 2 && formRefs.current?.rt) {
                                                                 formRefs.current.rt.select();
                                                       }
                                                  }}
                                                  ref={(el) => {
                                                       if (formRefs.current) {
                                                            formRefs.current['rw'] = el;
                                                       }
                                                  }}
                                             />
                                             <span className='text-3xl mx-1'>/</span>
                                             <Input
                                                  className='w-1/4'
                                                  type='number'
                                                  required
                                                  min={1}
                                                  maxLength={3}
                                                  minLength={3}
                                                  placeholder='Masukan RT' 
                                                  value={dataForm.rt_baru??""}
                                                  onChange={(e) => {
                                                       const value = e.target.value;
                                                       setDataForm((prevData: any) => ({ ...prevData, rt_baru: value }));
                                                       if (value.length === 3 && formRefs.current?.jalan) {
                                                                 formRefs.current.jalan.select();
                                                       }
                                                  }}
                                                  ref={(el) => {
                                                       if (formRefs.current) {
                                                            formRefs.current['rt'] = el;
                                                       }
                                                  }}
                                             />
                                        </td>
                                   </tr>
                                   <tr>
                                        <td className='px-2'>JALAN</td>
                                        <td>:</td>
                                        <td>{dataForm.jalan_lama ? dataForm.jalan_lama : '-'}</td>                                       
                                        <td>
                                             <Input 
                                                  className='mb-3'
                                                  placeholder='Masukan Jalan'
                                                  value={dataForm.jalan_baru??""}
                                                  onChange={(e) => {
                                                       const value = e.target.value;
                                                       setDataForm((prevData: any) => ({ ...prevData, rt_baru: value }));
                                                       if (value.length === 3 && formRefs.current?.jalan) {
                                                                 formRefs.current.jalan.select();
                                                       }
                                                  }}
                                                  ref={(el) => {
                                                       if (formRefs.current) {
                                                            formRefs.current['jalan'] = el;
                                                       }
                                                  }}
                                             />
                                        </td>
                                   </tr>
                                   <tr>
                                        <td className='px-2'>BLOK/KAV/NO</td>
                                        <td>:</td>
                                        <td>{dataForm.blok_kav_no_lama ? dataForm.blok_kav_no_lama : '-'}</td>
                                        <td>
                                             <Input 
                                                  value={dataForm.blok_kav_no_baru??""}
                                                  onChange={(e) => {
                                                       setDataForm((prevData: any) => ({ ...prevData, blok_kav_no_baru: e.target.value }));
                                                  }}
                                                  className='w-1/3'
                                                  placeholder='Masukan Blok/Kav/No'
                                             />
                                        </td>
                                   </tr>
                              </tbody>
                         </table>
                         <div className="flex justify-end items-center mt-5">
                         <Button type="submit" disabled={loadingSaveOrUpdate}> {loadingSaveOrUpdate ? (<Loader2 className="animate-spin" />) : (<Save /> )} Simpan</Button>
                         </div>
                    </form>
            </DialogContent>
        </Dialog>
    );
}