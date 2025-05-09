import LoadingData from '@/components/data-table/loading-data';
import NoData from '@/components/data-table/no-data';

export default function Realisasi({gate, loading, data}:any) {
    return (
          <div className='mt-5'>
               <hr />
               <p className='text-2xl text-center font-semibold'>REKAP SPPT PBB-P2</p>
               {gate.realisasi && (
                    <table className="w-full text-left text-sm border-collapse border mt-5">
                         <thead className='text-center'>
                              <tr className="uppercase leading-normal">
                                   <th className="p-1 border w-1" rowSpan={2}>BUKU</th>
                                   <th className="p-1 border" colSpan={2}>KETETAPAN AWAL</th>
                                   <th className="p-1 border" colSpan={2}>REALISASI PENYAMPAIAN</th>
                                   <th className="p-1 border w-1" rowSpan={2}>%</th>
                                   <th className="p-1 border" colSpan={2}>KETETAPAN BERJALAN</th>
                                   <th className="p-1 border" colSpan={2}>REALISASI PEMBAYARAN<br/>BERDASARKAN KETETAPAN</th>
                                   <th className="p-1 border w-1" rowSpan={2}>%</th>
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
                              <tr className="leading-normal bg-gray-100 dark:bg-slate-900 text-[12px]">
                                   <th className="p-1 border">1</th>
                                   <th className="p-1 border">2</th>
                                   <th className="p-1 border">3</th>
                                   <th className="p-1 border">4</th>
                                   <th className="p-1 border">5</th>
                                   <th className="p-1 border">(5/3)</th>
                                   <th className="p-1 border">7</th>
                                   <th className="p-1 border">8</th>
                                   <th className="p-1 border">9</th>
                                   <th className="p-1 border">10</th>
                                   <th className="p-1 border">(9/7)</th>
                              </tr>
                         </thead>
                         <tbody className="font-light">
                              {loading && <LoadingData colSpan={11}/>}
                              {Object.entries(data).length > 0 ? Object.entries(data).map(([key, value]:any, index) => (
                              <tr key={key} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                                   <td className="px-2 py-1 border text-center">{key}</td>
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
                              )):(!loading ?<NoData colSpan={11}/>: null)}
                         </tbody>
                    </table>
               )}
          </div>
    );
}
