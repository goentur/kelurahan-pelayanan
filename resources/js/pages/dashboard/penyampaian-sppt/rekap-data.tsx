import LoadingData from '@/components/data-table/loading-data';
import NoData from '@/components/data-table/no-data';
import React from 'react';

export default function RekapData({loading, data} : any) {
    return (
    <div className='mt-5'>
          REKAP PENYAMPAIAN SPPT PBB P-2
          <table className="w-full text-left text-sm border-collapse border">
               <thead className='text-center'>
                    <tr className="uppercase leading-normal">
                         <th className="p-1 border w-1/4" rowSpan={2}>SATUAN KERJA</th>
                         <th className="p-1 border" colSpan={2}>KETETAPAN AWAL</th>
                         <th className="p-1 border" colSpan={2}>TERSAMPAIKAN</th>
                         <th className="p-1 border" rowSpan={2}>%</th>
                         <th className="p-1 border" colSpan={2}>TIDAK TERSAMPAIKAN</th>
                         <th className="p-1 border" rowSpan={2}>%</th>
                         <th className="p-1 border" colSpan={2}>SISA</th>
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
                         <th className="p-1 border">(7/2)</th>
                         <th className="p-1 border">10</th>
                         <th className="p-1 border">11</th>
                         <th className="p-1 border">(10/2)</th>
                    </tr>
               </thead>
               <tbody className="font-light">
                    {loading && <LoadingData colSpan={12}/>}
                    {Object.entries(data).length > 0 ? Object.entries(data).map(([key, value]: any, index) => (
                         <React.Fragment key={`atasan-${key}`}>
                         <tr className="bg-gray-100 dark:bg-slate-900 font-semibold">
                              <td className="px-2 py-1 border text-start">{value.nama}</td>
                              <td className="px-2 py-1 border text-end">{value.jumlah_baku}</td>
                              <td className="px-2 py-1 border text-end">{value.jumlah_nominal}</td>
                              <td className="px-2 py-1 border text-end">{value.jumlah_tersampaikan}</td>
                              <td className="px-2 py-1 border text-end">{value.nominal_tersampaikan}</td>
                              <td className="px-2 py-1 border text-end">{value.persen_tersampaikan}</td>
                              <td className="px-2 py-1 border text-end">{value.jumlah_tidak}</td>
                              <td className="px-2 py-1 border text-end">{value.nominal_tidak}</td>
                              <td className="px-2 py-1 border text-end">{value.persen_tidak}</td>
                              <td className="px-2 py-1 border text-end">{value.jumlah_sisa}</td>
                              <td className="px-2 py-1 border text-end">{value.nominal_sisa}</td>
                              <td className="px-2 py-1 border text-end">{value.persen_sisa}</td>
                         </tr>

                         {value.bawahan && Array.isArray(value.bawahan) && value.bawahan.map((detail: any, idx: number) => (
                              <tr key={`bawahan-${key}-${idx}`} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                                   <td className="px-2 py-1 border text-start">{detail.nama}</td>
                                   <td className="px-2 py-1 border text-end">{detail.jumlah_baku}</td>
                                   <td className="px-2 py-1 border text-end">{detail.jumlah_nominal}</td>
                                   <td className="px-2 py-1 border text-end">{detail.jumlah_tersampaikan}</td>
                                   <td className="px-2 py-1 border text-end">{detail.nominal_tersampaikan}</td>
                                   <td className="px-2 py-1 border text-end">{detail.persen_tersampaikan}</td>
                                   <td className="px-2 py-1 border text-end">{detail.jumlah_tidak}</td>
                                   <td className="px-2 py-1 border text-end">{detail.nominal_tidak}</td>
                                   <td className="px-2 py-1 border text-end">{detail.persen_tidak}</td>
                                   <td className="px-2 py-1 border text-end">{detail.jumlah_sisa}</td>
                                   <td className="px-2 py-1 border text-end">{detail.nominal_sisa}</td>
                                   <td className="px-2 py-1 border text-end">{detail.persen_sisa}</td>
                              </tr>
                         ))}
                         </React.Fragment>
                    )) : (!loading ? <NoData colSpan={12} /> : null)}

               </tbody>
          </table>
    </div>
    );
}
