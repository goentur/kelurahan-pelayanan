import LoadingData from '@/components/data-table/loading-data';
import NoData from '@/components/data-table/no-data';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { ChevronDown, ChevronUp } from 'lucide-react';
import React, { useEffect, useState } from 'react';


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
    const title = "Rekap SPPT PBB-P2 Tahun "+tahun
    const [loadingData, setLoadingData] = useState(false);
    const [loadingDataPerKelurahan, setLoadingDataPerKelurahan] = useState(false);
    const [dataTableRealisasi, setDataTableRealisasi] = useState<[]>([]);
    const [dataTableRealisasiPerKelurahan, setDataTableRealisasiPerKelurahan] = useState<[]>([]);
    useEffect(() => {
        // if(gate.realisasi) getData();
        if(gate.per_kelurahan) getDataPerKelurahan();
    }, []);
    const [expandedRows, setExpandedRows] = useState<{ [key: string]: boolean }>({});

    const toggleRowExpansion = (key: string) => {
    setExpandedRows(prev => ({
        ...prev,
        [key]: !prev[key], // Toggle ekspansi baris
    }));
    };

    const getData = async () => {
        setLoadingData(true);
        try {
            const response = await axios.post(route('dashboard.realisasi.data'));
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
            const response = await axios.post(route('dashboard.realisasi.data-per-kelurahan'));
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
                        {gate.realisasi && (
                            <table className="w-full text-left text-sm border-collapse border">
                                <thead className='text-center'>
                                    <tr className="uppercase leading-normal">
                                        <th className="p-1 border w-1" rowSpan={2}>BUKU</th>
                                        <th className="p-1 border" colSpan={2}>KETETAPAN AWAL</th>
                                        <th className="p-1 border" colSpan={2}>REALISASI PENYAMPAIAN</th>
                                        <th className="p-1 border" colSpan={2}>SPPT PBB-P2</th>
                                        <th className="p-1 border" colSpan={2}>REALISASI PEMBAYARAN<br/>BERDASARKAN KETETAPAN</th>
                                    </tr>
                                    <tr className="leading-normal">
                                        <th className="p-1 border">JUMLAH<br/>OBJEK PAJAK</th>
                                        <th className="p-1 border w-1">NOMINAL (Rp)</th>
                                        <th className="p-1 border">JUMLAH<br/>OBJEK PAJAK</th>
                                        <th className="p-1 border w-1">NOMINAL (Rp)</th>
                                        <th className="p-1 border">JUMLAH<br/>OBJEK PAJAK</th>
                                        <th className="p-1 border w-1">NOMINAL (Rp)</th>
                                        <th className="p-1 border">JUMLAH<br/>OBJEK PAJAK</th>
                                        <th className="p-1 border w-1">NOMINAL (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody className="font-light">
                                    {loadingData && <LoadingData colSpan={9}/>}
                                    {Object.entries(dataTableRealisasi).length > 0 ? Object.entries(dataTableRealisasi).map(([key, value]:any, index) => (
                                    <tr key={key} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                                        <td className="px-2 py-1 border text-center">{key}</td>
                                        <td className="px-2 py-1 border text-end">{value.bakuAwal?.sppt}</td>
                                        <td className="px-2 py-1 border text-end">{value.bakuAwal?.jumlah}</td>
                                        <td className="px-2 py-1 border text-end">{value.penyampaian?.sppt}</td>
                                        <td className="px-2 py-1 border text-end">{value.penyampaian?.jumlah}</td>
                                        <td className="px-2 py-1 border text-end">{value.sppt?.sppt}</td>
                                        <td className="px-2 py-1 border text-end">{value.sppt?.jumlah}</td>
                                        <td className="px-2 py-1 border text-end">{value.pembayaran?.sppt}</td>
                                        <td className="px-2 py-1 border text-end">{value.pembayaran?.jumlah}</td>
                                    </tr>
                                    )):(!loadingData ?<NoData colSpan={9}/>: null)}
                                </tbody>
                            </table>
                        )}
                        {gate.per_kelurahan && (
                            <table className="w-full text-left text-sm border-collapse border mt-5">
                                <thead className='text-center'>
                                    <tr className="uppercase leading-normal">
                                        <th className="p-1 border w-1/4" rowSpan={2}>SATUAN KERJA</th>
                                        <th className="p-1 border" colSpan={2}>KETETAPAN AWAL</th>
                                        <th className="p-1 border" colSpan={2}>REALISASI PENYAMPAIAN</th>
                                        <th className="p-1 border" colSpan={2}>SPPT PBB-P2</th>
                                        <th className="p-1 border" colSpan={2}>REALISASI PEMBAYARAN<br/>BERDASARKAN KETETAPAN</th>
                                    </tr>
                                    <tr className="leading-normal">
                                        <th className="p-1 border">JUMLAH<br/>OBJEK PAJAK</th>
                                        <th className="p-1 border w-1">NOMINAL (Rp)</th>
                                        <th className="p-1 border">JUMLAH<br/>OBJEK PAJAK</th>
                                        <th className="p-1 border w-1">NOMINAL (Rp)</th>
                                        <th className="p-1 border">JUMLAH<br/>OBJEK PAJAK</th>
                                        <th className="p-1 border w-1">NOMINAL (Rp)</th>
                                        <th className="p-1 border">JUMLAH<br/>OBJEK PAJAK</th>
                                        <th className="p-1 border w-1">NOMINAL (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody className="font-light">
                                    {loadingDataPerKelurahan && <LoadingData colSpan={9} />}
                                        {Object.entries(dataTableRealisasiPerKelurahan).length > 0 ? (
                                            Object.entries(dataTableRealisasiPerKelurahan).map(([key, value]: any) => (
                                                <React.Fragment key={key}>
                                                    {/* Row Kelurahan */}
                                                    <tr className="hover:bg-gray-100 dark:hover:bg-slate-900 font-bold">
                                                        <td className="px-2 py-1 border">{value.nama}</td>
                                                        <td className="px-2 py-1 border text-end">{value.bakuAwal?.sppt}</td>
                                                        <td className="px-2 py-1 border text-end">{value.bakuAwal?.jumlah}</td>
                                                        <td className="px-2 py-1 border text-end">{value.penyampaian?.sppt}</td>
                                                        <td className="px-2 py-1 border text-end">{value.penyampaian?.jumlah}</td>
                                                        <td className="px-2 py-1 border text-end">{value.sppt?.sppt}</td>
                                                        <td className="px-2 py-1 border text-end">{value.sppt?.jumlah}</td>
                                                        <td className="px-2 py-1 border text-end">{value.pembayaran?.sppt}</td>
                                                        <td className="px-2 py-1 border text-end">{value.pembayaran?.jumlah}</td>
                                                    </tr>

                                                    {/* Row Bawahan */}
                                                    {value.bawahan && Object.entries(value.bawahan).map(([keyBawahan, valueBawahan]: any) => (
                                                        <React.Fragment key={keyBawahan}>
                                                            <tr
                                                                className={`hover:bg-gray-100 dark:hover:bg-slate-900 ${Object.entries(valueBawahan.kelurahan).length > 1 ? "cursor-pointer" : ""}`}
                                                                onClick={() => toggleRowExpansion(keyBawahan)}
                                                            >
                                                                <td className="px-2 py-1 border flex justify-between">
                                                                {valueBawahan.nama}
                                                                {Object.entries(valueBawahan.kelurahan).length > 1 && (
                                                                    expandedRows[keyBawahan] ? <ChevronUp size={20}/> : <ChevronDown size={20}/>
                                                                )}
                                                                </td>

                                                                <td className="px-2 py-1 border text-end">{valueBawahan.bakuAwal?.sppt}</td>
                                                                <td className="px-2 py-1 border text-end">{valueBawahan.bakuAwal?.jumlah}</td>
                                                                <td className="px-2 py-1 border text-end">{valueBawahan.penyampaian?.sppt}</td>
                                                                <td className="px-2 py-1 border text-end">{valueBawahan.penyampaian?.jumlah}</td>
                                                                <td className="px-2 py-1 border text-end">{valueBawahan.sppt?.sppt}</td>
                                                                <td className="px-2 py-1 border text-end">{valueBawahan.sppt?.jumlah}</td>
                                                                <td className="px-2 py-1 border text-end">{valueBawahan.pembayaran?.sppt}</td>
                                                                <td className="px-2 py-1 border text-end">{valueBawahan.pembayaran?.jumlah}</td>
                                                            </tr>

                                                            {valueBawahan.kelurahan && Object.entries(valueBawahan.kelurahan).length > 1 && Object.entries(valueBawahan.kelurahan).map(([keyKelurahan, valueKelurahan]: any) => (
                                                                <tr
                                                                    key={keyKelurahan}
                                                                    className={`${expandedRows[keyBawahan] ? 'table-row' : 'hidden'} hover:bg-gray-100 dark:hover:bg-slate-900`}
                                                                >
                                                                    <td className="px-2 py-1 border">{valueKelurahan.nama}</td>
                                                                    <td className="px-2 py-1 border text-end">{valueKelurahan.bakuAwal?.sppt}</td>
                                                                    <td className="px-2 py-1 border text-end">{valueKelurahan.bakuAwal?.jumlah}</td>
                                                                    <td className="px-2 py-1 border text-end">{valueKelurahan.penyampaian?.sppt}</td>
                                                                    <td className="px-2 py-1 border text-end">{valueKelurahan.penyampaian?.jumlah}</td>
                                                                    <td className="px-2 py-1 border text-end">{valueKelurahan.sppt?.sppt}</td>
                                                                    <td className="px-2 py-1 border text-end">{valueKelurahan.sppt?.jumlah}</td>
                                                                    <td className="px-2 py-1 border text-end">{valueKelurahan.pembayaran?.sppt}</td>
                                                                    <td className="px-2 py-1 border text-end">{valueKelurahan.pembayaran?.jumlah}</td>
                                                                </tr>
                                                            ))}
                                                        </React.Fragment>
                                                    ))}
                                                </React.Fragment>
                                            ))
                                        ) : (
                                            !loadingDataPerKelurahan && <NoData colSpan={9} />
                                        )}
                                </tbody>
                            </table>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
