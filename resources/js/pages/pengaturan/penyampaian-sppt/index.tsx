import Combobox from '@/components/combobox';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { getTahunOptions, mapToOptions } from '@/lib/utils';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { Loader2, Play } from 'lucide-react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Pengaturan',
        href: 'pengaturan.aplikasi.index',
    },
    {
        title: 'Penyampaian SPPT',
        href: 'pengaturan.penyampaian-sppt.index',
    },
];

export default function Index({jenisLapor, users}:any) {
    const title = "Penyampaian SPPT"
    const [loadingResetLaporanPenyampaianSppt, setLoadingResetLaporanPenyampaianSppt] = useState(false);
    const [dataResetLaporanPenyampaian, setDataResetLaporanPenyampaian] = useState({
        tahun: '',
        jenisLapor: '',
        kelurahan: '',
    });
    const submitResetLaporanPenyampaianSppt = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoadingResetLaporanPenyampaianSppt(true)
        try {
            const response = await axios.post(route("pengaturan.penyampaian-sppt.reset-data-laporan-penyampaian-sppt"),{
                tahun : dataResetLaporanPenyampaian.tahun,
                jenis : dataResetLaporanPenyampaian.jenisLapor,
                kelurahan : dataResetLaporanPenyampaian.kelurahan
            });            
        } catch (error:any) {
            alertApp(error.message, 'error');
        }finally{
             setLoadingResetLaporanPenyampaianSppt(false)
        }
   };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card className='w-1/2'>
                    <CardHeader>
                        <CardTitle className="text-xl">Reset Data Laporan</CardTitle>
                    </CardHeader>
                    <CardContent>
                            <Combobox label="tahun" selectedValue={dataResetLaporanPenyampaian.tahun} options={getTahunOptions()} onSelect={(value) => setDataResetLaporanPenyampaian((prevData:any) => ({ ...prevData, tahun: value }))} />
                                
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-5">
                                <Combobox
                                    label="Jenis Lapor"
                                    selectedValue={dataResetLaporanPenyampaian.jenisLapor}
                                    options={mapToOptions(jenisLapor)}
                                    onSelect={(value) => setDataResetLaporanPenyampaian((prevData: any) => ({ ...prevData, jenisLapor: value }))}
                                />
                                <Combobox
                                    label="Kelurahan"
                                    selectedValue={dataResetLaporanPenyampaian.kelurahan}
                                    options={users}
                                    onSelect={(value) => setDataResetLaporanPenyampaian((prevData: any) => ({ ...prevData, kelurahan: value }))}
                                />
                            </div>
                            <Button type="button" onClick={submitResetLaporanPenyampaianSppt} className='mt-2' disabled={loadingResetLaporanPenyampaianSppt || !dataResetLaporanPenyampaian.tahun || !dataResetLaporanPenyampaian.jenisLapor || !dataResetLaporanPenyampaian.kelurahan}> {loadingResetLaporanPenyampaianSppt ? (<Loader2 className="animate-spin" />) : (<Play /> )} Jalankan</Button>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    )
}
