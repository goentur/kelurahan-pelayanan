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
                              <th className="p-1 border" colSpan={2}>KETETAPAN BERJALAN</th>
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
                              {loading && <LoadingData colSpan={9}/>}
                              {Object.entries(data).length > 0 ? Object.entries(data).map(([key, value]:any, index) => (
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
                              )):(!loading ?<NoData colSpan={9}/>: null)}
                         </tbody>
                    </table>
               )}
          </div>
    );
}
