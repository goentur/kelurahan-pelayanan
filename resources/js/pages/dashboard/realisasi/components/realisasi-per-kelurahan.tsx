import LoadingData from '@/components/data-table/loading-data';
import NoData from '@/components/data-table/no-data';
import { ChevronDown, ChevronUp, CornerDownRight } from 'lucide-react';
import React, { useState } from 'react';

export default function RealisasiPerKelurahan({gate, loading, data}:any) {
    const [expandedRows, setExpandedRows] = useState<{ [key: string]: boolean }>({});

    const toggleRowExpansion = (key: string) => {
        setExpandedRows(prev => ({
            ...prev,
            [key]: !prev[key],
        }));
    };
    return (
        <div>
            {gate.per_kelurahan && (
                <div className='mt-5'>
                    <p className='text-2xl text-center font-semibold'>REKAP SPPT PBB-P2 BERDASARKAN KELURAHAN</p>
                    <table className="w-full text-left text-sm border-collapse border mt-5">
                        <thead className='text-center'>
                            <tr className="uppercase leading-normal">
                                <th className="p-1 border w-1/4" rowSpan={2}>SATUAN KERJA</th>
                                <th className="p-1 border" colSpan={2}>KETETAPAN AWAL</th>
                                <th className="p-1 border" colSpan={2}>REALISASI PENYAMPAIAN</th>
                                <th className="p-1 border" rowSpan={2}>%</th>
                                <th className="p-1 border" colSpan={2}>KETETAPAN BERJALAN</th>
                                <th className="p-1 border" colSpan={2}>REALISASI PEMBAYARAN<br/>BERDASARKAN KETETAPAN</th>
                                <th className="p-1 border" rowSpan={2}>%</th>
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
                            <tr className="leading-normal text-[12px]">
                                <th className="p-1 border">1</th>
                                <th className="p-1 border">2</th>
                                <th className="p-1 border">3</th>
                                <th className="p-1 border">4</th>
                                <th className="p-1 border">5</th>
                                <th className="p-1 border">(4/2)</th>
                                <th className="p-1 border">7</th>
                                <th className="p-1 border">8</th>
                                <th className="p-1 border">9</th>
                                <th className="p-1 border">10</th>
                                <th className="p-1 border">(10/8)</th>
                            </tr>
                        </thead>
                        <tbody className="font-light">
                            {loading && <LoadingData colSpan={11} />}
                                {Object.entries(data).length > 0 ? (
                                    Object.entries(data).map(([key, value]: any) => (
                                        <React.Fragment key={key}>
                                            <tr className="bg-gray-100 dark:bg-slate-900 font-bold">
                                                <td className="px-2 py-1 border">{value.nama}</td>
                                                <td className="px-2 py-1 border text-end">{value.bakuAwal?.sppt}</td>
                                                <td className="px-2 py-1 border text-end">{value.bakuAwal?.jumlah}</td>
                                                <td className="px-2 py-1 border text-end">{value.penyampaian?.sppt}</td>
                                                <td className="px-2 py-1 border text-end">{value.penyampaian?.jumlah}</td>
                                                <td className="px-2 py-1 border text-end italic">{value.penyampaian?.persen}</td>
                                                <td className="px-2 py-1 border text-end">{value.sppt?.sppt}</td>
                                                <td className="px-2 py-1 border text-end">{value.sppt?.jumlah}</td>
                                                <td className="px-2 py-1 border text-end">{value.pembayaran?.sppt}</td>
                                                <td className="px-2 py-1 border text-end">{value.pembayaran?.jumlah}</td>
                                                <td className="px-2 py-1 border text-end italic">{value.pembayaran?.persen}</td>
                                            </tr>
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
                                                        <td className="px-2 py-1 border text-end italic">{valueBawahan.penyampaian?.persen}</td>
                                                        <td className="px-2 py-1 border text-end">{valueBawahan.sppt?.sppt}</td>
                                                        <td className="px-2 py-1 border text-end">{valueBawahan.sppt?.jumlah}</td>
                                                        <td className="px-2 py-1 border text-end">{valueBawahan.pembayaran?.sppt}</td>
                                                        <td className="px-2 py-1 border text-end">{valueBawahan.pembayaran?.jumlah}</td>
                                                        <td className="px-2 py-1 border text-end italic">{valueBawahan.pembayaran?.persen}</td>
                                                    </tr>

                                                    {valueBawahan.kelurahan && Object.entries(valueBawahan.kelurahan).length > 1 && Object.entries(valueBawahan.kelurahan).map(([keyKelurahan, valueKelurahan]: any) => (
                                                        <tr
                                                            key={keyKelurahan}
                                                            className={`${expandedRows[keyBawahan] ? 'table-row' : 'hidden'} hover:bg-gray-100 dark:hover:bg-slate-900`}
                                                        >
                                                            <td className="px-2 py-1 border flex justify-start"><CornerDownRight size={18}/> {valueKelurahan.nama}</td>
                                                            <td className="px-2 py-1 border text-end">{valueKelurahan.bakuAwal?.sppt}</td>
                                                            <td className="px-2 py-1 border text-end">{valueKelurahan.bakuAwal?.jumlah}</td>
                                                            <td className="px-2 py-1 border text-end">{valueKelurahan.penyampaian?.sppt}</td>
                                                            <td className="px-2 py-1 border text-end">{valueKelurahan.penyampaian?.jumlah}</td>
                                                            <td className="px-2 py-1 border text-end italic">{valueKelurahan.penyampaian?.persen}</td>
                                                            <td className="px-2 py-1 border text-end">{valueKelurahan.sppt?.sppt}</td>
                                                            <td className="px-2 py-1 border text-end">{valueKelurahan.sppt?.jumlah}</td>
                                                            <td className="px-2 py-1 border text-end">{valueKelurahan.pembayaran?.sppt}</td>
                                                            <td className="px-2 py-1 border text-end">{valueKelurahan.pembayaran?.jumlah}</td>
                                                            <td className="px-2 py-1 border text-end italic">{valueKelurahan.pembayaran?.persen}</td>
                                                        </tr>
                                                    ))}
                                                </React.Fragment>
                                            ))}
                                        </React.Fragment>
                                    ))
                                ) : (
                                    !loading && <NoData colSpan={11} />
                                )}
                        </tbody>
                    </table>
                </div>
            )}
        </div>
    );
}
