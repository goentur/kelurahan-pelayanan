import Combobox from '@/components/combobox';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { getTahunOptions } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useState } from 'react';
import Data from './data';
import RekapData from './rekap-data';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Dashboard',
        href: 'dashboard.penyampaian-sppt.index',
    },
    {
        title: 'Penyampaian SPPT',
        href: 'dashboard.penyampaian-sppt.index',
    },
];

export default function Index({jenisLapor, tahunPajak} : any) {
    const title = "Penyampaian SPPT"
    const [data, setData] = useState({
        tahun: tahunPajak,
    });
    const [loadingData, setLoadingData] = useState(false);
    const [penyampaianData, setPenyampaianData] = useState<[]>([]);
    const [loadingRekapData, setLoadingRekapData] = useState(false);
    const [rekapData, setRekapData] = useState<[]>([]);
    
    useEffect(() => {
        getPenyampaianData();
        getRekapData();
    }, [data.tahun]);

    const getPenyampaianData = async () => {
        setLoadingData(true);
        try {
            const response = await axios.post(route('dashboard.penyampaian-sppt.data'),data);
            setPenyampaianData(response.data);
        } catch (error:any) {
            alertApp(error.message, 'error');
        } finally {
            setLoadingData(false);
        }
    };
    const getRekapData = async () => {
        setLoadingRekapData(true);
        try {
            const response = await axios.post(route('dashboard.penyampaian-sppt.rekap-data'),data);
            setRekapData(response.data);
        } catch (error:any) {
            alertApp(error.message, 'error');
        } finally {
            setLoadingRekapData(false);
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
                            <Combobox label="tahun" selectedValue={data.tahun} options={getTahunOptions()} onSelect={(value) =>{ setData((prevData:any) => ({ ...prevData, tahun: value }))}} disabled={loadingData || loadingRekapData} />
                        </div>
                        <Data jenisLapor={jenisLapor} loading={loadingData} data={penyampaianData}/>
                        <RekapData loading={loadingRekapData} data={rekapData}/>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
