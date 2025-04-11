import { Button } from '@/components/ui/button';
import { Transition } from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import { Eraser, Loader2 } from 'lucide-react';

export default function OptimizeClear() {
     const { processing, post, recentlySuccessful } = useForm();

     const handleOptimizeClear =(e: React.FormEvent) => {
          e.preventDefault();
          post(route('pengaturan.aplikasi.optimize-clear'));
     };
     return (
          <div className="flex items-center gap-4">
               <Button type="button" onClick={handleOptimizeClear} disabled={processing}>
                    {processing ? <Loader2 className="animate-spin" /> : <Eraser/>} Optimize : Clear
               </Button>
               <Transition
                    show={recentlySuccessful}
                    enter="transition ease-in-out"
                    enterFrom="opacity-0"
                    leave="transition ease-in-out"
                    leaveTo="opacity-0"
               >
                    <p className="text-sm text-green-600 dark:text-green-400">
                         Selesai.
                    </p>
               </Transition>
          </div>
     )
}
