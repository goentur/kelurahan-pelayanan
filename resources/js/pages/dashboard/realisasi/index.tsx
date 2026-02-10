import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useState } from 'react';
import RealisasiPerKelurahan from './components/realisasi-per-kelurahan';
import Realisasi from './components/realisasi';
import Combobox from '@/components/combobox';
import { getTahunOptions } from '@/lib/utils';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Dashboard',
        href: 'dashboard.realisasi.index',
    },
    {
        title: 'Realisasi',
        href: 'dashboard.realisasi.index',
    },
];

export default function Index({tahun, gate}:any) {
    const [loadingData, setLoadingData] = useState(false);
    const [loadingDataPerKelurahan, setLoadingDataPerKelurahan] = useState(false);
    const [dataTableRealisasi, setDataTableRealisasi] = useState<[]>([]);
    const [dataTableRealisasiPerKelurahan, setDataTableRealisasiPerKelurahan] = useState<[]>([]);
    const [data, setData] = useState({
        tahun: tahun,
    });
    const title = "Rekap SPPT PBB-P2 Tahun "+data.tahun

    useEffect(() => {
        if(gate.realisasi) getData();
        if(gate.per_kelurahan) getDataPerKelurahan();
    }, [data.tahun]);

    const getData = async () => {
        setLoadingData(true);
        try {
            const response = await axios.post(route('dashboard.realisasi.data'),data);
            setDataTableRealisasi(response.data);
        } catch (error:any) {
            alertApp(error.message, 'error');
        } finally {
            setLoadingData(false);
        }
    };
    const getDataPerKelurahan = async () => {
        setLoadingDataPerKelurahan(true);
        try {
            const response = await axios.post(route('dashboard.realisasi.data-per-kelurahan'),data);
            setDataTableRealisasiPerKelurahan(response.data);
        } catch (error:any) {
            alertApp(error.message, 'error');
        } finally {
            setLoadingDataPerKelurahan(false);
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
                        <div className="mb-5">
                            <Combobox label="tahun" selectedValue={data.tahun} options={getTahunOptions()} onSelect={(value) =>{ setData((prevData:any) => ({ ...prevData, tahun: value }))}} disabled={loadingDataPerKelurahan || loadingData} />
                        </div>
                        <RealisasiPerKelurahan gate={gate} loading={loadingDataPerKelurahan} data={dataTableRealisasiPerKelurahan}/>
                        <Realisasi gate={gate} loading={loadingData} data={dataTableRealisasi}/>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
