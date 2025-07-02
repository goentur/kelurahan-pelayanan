import LoadingData from '@/components/data-table/loading-data';
import NoData from '@/components/data-table/no-data';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import React, { useEffect, useState } from 'react';
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

export default function Index({jenisLapor} : any) {
    const title = "Penyampaian SPPT"
    const [loadingData, setLoadingData] = useState(false);
    const [data, setData] = useState<[]>([]);
    const [loadingRekapData, setLoadingRekapData] = useState(false);
    const [rekapData, setRekapData] = useState<[]>([]);
    
    useEffect(() => {
        getData();
        getRekapData();
    }, []);

    const getData = async () => {
        setLoadingData(true);
        try {
            const response = await axios.post(route('dashboard.penyampaian-sppt.data'));
            setData(response.data);
        } catch (error:any) {
            alertApp(error.message, 'error');
        } finally {
            setLoadingData(false);
        }
    };
    const getRekapData = async () => {
        setLoadingRekapData(true);
        try {
            const response = await axios.post(route('dashboard.penyampaian-sppt.rekap-data'));
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
                        <Data jenisLapor={jenisLapor} loading={loadingData} data={data}/>
                        <RekapData loading={loadingRekapData} data={rekapData}/>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
