import { Head, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '@/layouts/app-layout';
import type { SharedProps } from '@/types';

type Item = { id: number; sku: string; name: string; unit: string; reorder_level: string };
type Warehouse = { id: number; name: string; code: string; company: { name: string }; project?: { name: string } | null };
type Balance = { id: number; quantity_on_hand: string; quantity_reserved: string; warehouse: { name: string }; item: Item };

export default function InventoryIndex({ items, warehouses, balances }: { items: Item[]; warehouses: Warehouse[]; balances: Balance[] }) {
    const { auth } = usePage<SharedProps>().props;
    const canMove = auth.user?.permissions.includes('inventory.move') ?? false;
    const [warehouseId, setWarehouseId] = useState('');
    const [itemId, setItemId] = useState('');
    const [type, setType] = useState('receipt');
    const [quantity, setQuantity] = useState('1');

    const submit = (event: FormEvent) => {
        event.preventDefault();
        router.post('/inventory/movements', {
            warehouse_id: Number(warehouseId), inventory_item_id: Number(itemId),
            project_id: null, movement_type: type, quantity: Number(quantity),
            reference_type: null, reference_id: null, notes: null, occurred_at: null,
        }, { preserveScroll: true });
    };

    return <AppLayout title="Inventory">
        <Head title="Inventory" />
        <div className="space-y-6">
            {canMove && <section className="rounded-xl border border-slate-200 bg-white p-5">
                <h2 className="font-semibold">Record stock movement</h2>
                <form onSubmit={submit} className="mt-4 grid gap-3 md:grid-cols-4">
                    <select className="input" required value={warehouseId} onChange={e => setWarehouseId(e.target.value)}>
                        <option value="">Warehouse</option>{warehouses.map(w => <option key={w.id} value={w.id}>{w.name}</option>)}
                    </select>
                    <select className="input" required value={itemId} onChange={e => setItemId(e.target.value)}>
                        <option value="">Item</option>{items.map(i => <option key={i.id} value={i.id}>{i.sku} — {i.name}</option>)}
                    </select>
                    <select className="input" value={type} onChange={e => setType(e.target.value)}>
                        <option value="receipt">Receipt</option><option value="issue">Issue</option><option value="return">Return</option>
                    </select>
                    <input className="input" type="number" min="0.0001" step="0.0001" value={quantity} onChange={e => setQuantity(e.target.value)} />
                    <button className="rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white md:col-span-4" type="submit">Record movement</button>
                </form>
            </section>}
            <section className="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <table className="min-w-full divide-y divide-slate-200 text-sm">
                    <thead className="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500"><tr><th className="px-4 py-3">Warehouse</th><th className="px-4 py-3">Item</th><th className="px-4 py-3">On hand</th><th className="px-4 py-3">Reserved</th></tr></thead>
                    <tbody className="divide-y divide-slate-100">{balances.map(b => <tr key={b.id}><td className="px-4 py-4">{b.warehouse.name}</td><td className="px-4 py-4">{b.item.sku} — {b.item.name}</td><td className="px-4 py-4">{b.quantity_on_hand} {b.item.unit}</td><td className="px-4 py-4">{b.quantity_reserved}</td></tr>)}</tbody>
                </table>
            </section>
        </div>
    </AppLayout>;
}
