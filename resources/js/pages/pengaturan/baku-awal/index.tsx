import FormInput from '@/components/form-input';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Transition } from '@headlessui/react';
import { Head, useForm } from '@inertiajs/react';
import { Loader2, Save } from 'lucide-react';
import { useRef } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Pengaturan',
        href: 'pengaturan.baku-awal.index',
    },
    {
        title: 'Baku Awal',
        href: 'pengaturan.baku-awal.index',
    },
];

export default function Index() {
     const title = 'Baku Awal'
     const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
     const tahun = new Date().getFullYear();

     const { data, setData, errors, processing, post, recentlySuccessful } = useForm({
          tahun_pajak : tahun,
          kd_propinsi : 33,
          kd_dati2 : 75,
          kd_kecamatan : "",
          kd_kelurahan : "",
          kd_blok : "",
          no_urut : "",
     });

    const handleForm = (e: React.FormEvent) => {
          e.preventDefault();
          post(route('pengaturan.aplikasi.baku-awal'));
     };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-xl">Form {title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                         <form onSubmit={handleForm} className='grid grid-cols-3 gap-4'>
                              <FormInput
                                   id="tahun_pajak"
                                   type="text"
                                   value={data.tahun_pajak}
                                   onChange={(e) => {
                                        const value = e.target.value;
                                        setData((prevData: any) => ({ ...prevData, tahun_pajak: value }));

                                        if (value.length === 4 && formRefs.current?.kd_propinsi) {
                                             formRefs.current.kd_propinsi.focus();
                                        }
                                   }}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['tahun_pajak'] = el;
                                        }
                                   }}
                                   placeholder="Masukkan tahun pajak"
                                   error={errors.tahun_pajak}
                                   required
                                   maxLength={4}
                              />
                              <FormInput
                                   id="kd_propinsi"
                                   type="text"
                                   value={data.kd_propinsi}
                                   onChange={(e) => {
                                        const value = e.target.value;
                                        setData((prevData: any) => ({ ...prevData, kd_propinsi: value }));

                                        if (value.length === 2 && formRefs.current?.kd_dati2) {
                                             formRefs.current.kd_dati2.focus();
                                        }
                                   }}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['kd_propinsi'] = el;
                                        }
                                   }}
                                   placeholder="Masukkan kd propinsi"
                                   error={errors.kd_propinsi}
                                   required
                                   maxLength={2}
                              />
                              <FormInput
                                   id="kd_dati2"
                                   type="text"
                                   value={data.kd_dati2}
                                   onChange={(e) => {
                                        const value = e.target.value;
                                        setData((prevData: any) => ({ ...prevData, kd_dati2: value }));

                                        if (value.length === 2 && formRefs.current?.kd_kecamatan) {
                                             formRefs.current.kd_kecamatan.focus();
                                        }
                                   }}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['kd_dati2'] = el;
                                        }
                                   }}
                                   placeholder="Masukkan kd dati2"
                                   error={errors.kd_dati2}
                                   required
                                   maxLength={2}
                              />
                              <FormInput
                                   id="kd_kecamatan"
                                   type="text"
                                   value={data.kd_kecamatan}
                                   onChange={(e) => {
                                        const value = e.target.value;
                                        setData((prevData: any) => ({ ...prevData, kd_kecamatan: value }));

                                        if (value.length === 3 && formRefs.current?.kd_kelurahan) {
                                             formRefs.current.kd_kelurahan.focus();
                                        }
                                   }}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['kd_kecamatan'] = el;
                                        }
                                   }}
                                   placeholder="Masukkan kd kecamatan"
                                   error={errors.kd_kecamatan}
                                   required
                                   autoFocus={true}
                                   maxLength={3}
                              />
                              <FormInput
                                   id="kd_kelurahan"
                                   type="text"
                                   value={data.kd_kelurahan}
                                   onChange={(e) => {
                                        const value = e.target.value;
                                        setData((prevData: any) => ({ ...prevData, kd_kelurahan: value }));

                                        if (value.length === 3 && formRefs.current?.kd_blok) {
                                             formRefs.current.kd_blok.focus();
                                        }
                                   }}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['kd_kelurahan'] = el;
                                        }
                                   }}
                                   placeholder="Masukkan kd kelurahan"
                                   error={errors.kd_kelurahan}
                                   required
                                   maxLength={3}
                              />
                              <FormInput
                                   id="kd_blok"
                                   type="text"
                                   value={data.kd_blok}
                                   onChange={(e) => {
                                        const value = e.target.value;
                                        setData((prevData: any) => ({ ...prevData, kd_blok: value }));

                                        if (value.length === 3 && formRefs.current?.no_urut) {
                                             formRefs.current.no_urut.focus();
                                        }
                                   }}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['kd_blok'] = el;
                                        }
                                   }}
                                   placeholder="Masukkan kd blok"
                                   error={errors.kd_blok}
                                   required
                                   maxLength={3}
                              />
                              <FormInput
                                   id="no_urut"
                                   type="text"
                                   value={data.no_urut}
                                   onChange={(e) => setData((prevData: any) => ({ ...prevData, no_urut: e.target.value }))}
                                   inputRef={(el) => {
                                        if (formRefs.current) {
                                             formRefs.current['no_urut'] = el;
                                        }
                                   }}
                                   placeholder="Masukkan no urut"
                                   error={errors.no_urut}
                                   required
                                   maxLength={4}
                              />
                              <div className="flex items-center mt-5">
                                   <Button type="submit" disabled={processing}> {processing ? (<Loader2 className="animate-spin" />) : (<Save /> )} Simpan</Button>
                                   <Transition
                                        show={recentlySuccessful}
                                        enter="transition ease-in-out"
                                        enterFrom="opacity-0"
                                        leave="transition ease-in-out"
                                        leaveTo="opacity-0"
                                   >
                                        <p className="text-sm text-green-600 dark:text-green-400 ms-5">
                                             Tersimpan
                                        </p>
                                   </Transition>
                              </div>
                         </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    )
}
