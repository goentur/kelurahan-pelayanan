import Combobox from '@/components/combobox';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { getTahunOptions, mapToOptions } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import axios from 'axios';
import { Loader2, Printer } from 'lucide-react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Cetak',
        href: 'cetak.penyampaian-sppt.index',
    },
    {
        title: 'Penyampaian SPPT',
        href: 'cetak.penyampaian-sppt.index',
    },
];

export default function Index({jenisLapor} : any) {
    const jenisLaporannya = { label : "SEMUA", value : "SEMUA" };
    const jenisLaporOptions = [jenisLaporannya, ...mapToOptions(jenisLapor)];
    const title = "Penyampaian SPPT"
    
    const [loadingTidakTersampikan, setLoadingTidakTersampikan] = useState(false);
    const [data, setData] = useState({
        tahun: '',
        jenisLapor: '',
    });
    const submitTidakTersampikan = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoadingTidakTersampikan(true)
        try {
            const response = await axios.post(route("cetak.penyampaian-sppt.tidak-tersampaikan",data.tahun),{},{
                responseType: 'blob',
            });
            const blob = new Blob([response.data], { type: response.headers['content-type'] });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'tidak-tersampaikan.pdf');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
        } catch (error:any) {
             if (error.response && error.response.data instanceof Blob) {
                  const text = await error.response.data.text();
                  try {
                       const json = JSON.parse(text);
                       alertApp(json.message || 'Terjadi kesalahan pada saat proses data.', 'error');
                  } catch {
                       alertApp('Terjadi kesalahan pada saat proses data.', 'error');
                  }
             } else {
                  alertApp(error.message || 'Terjadi kesalahan pada saat proses data.', 'error');
             }
        }finally{
             setLoadingTidakTersampikan(false)
        }
   };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-xl">{title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Combobox label="tahun" selectedValue={data.tahun} options={getTahunOptions()} onSelect={(value) => setData((prevData:any) => ({ ...prevData, tahun: value }))} />
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-5">
                            <div className="border p-4 rounded shadow">
                                <h3 className="text-lg font-semibold mb-2">Tersampaikan</h3>
                                <hr className="mb-4" />
                                <Combobox
                                    label="Jenis Lapor"
                                    selectedValue={data.jenisLapor}
                                    options={jenisLaporOptions}
                                    onSelect={(value) => setData((prevData: any) => ({ ...prevData, jenisLapor: value }))}
                                />
                                <Button type="submit" className='mt-2' disabled={loadingTidakTersampikan}> {loadingTidakTersampikan ? (<Loader2 className="animate-spin" />) : (<Printer /> )} Cetak</Button>
                            </div>

                            <div className="border p-4 rounded shadow">
                                <h3 className="text-lg font-semibold mb-2">Tidak Tersampaikan</h3>
                                <hr className="mb-4" />
                                <Button type="button" onClick={submitTidakTersampikan} disabled={loadingTidakTersampikan}> {loadingTidakTersampikan ? (<Loader2 className="animate-spin" />) : (<Printer /> )} Cetak</Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
