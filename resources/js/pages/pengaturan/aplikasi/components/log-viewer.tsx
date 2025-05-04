import { Button } from '@/components/ui/button';
import { History } from 'lucide-react';

export default function LogViewer() {
     return (
          <Button type="button" className='mt-5' onClick={() => window.open(route('log-viewer'), '_blank')}>
               <History /> Log Viewer
          </Button>
     )
}
