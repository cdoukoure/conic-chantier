@foreach($contacts as $contact)
<tr class="hover:bg-gray-50" id="contact-{{ $contact->id }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
            @if($contact->type == 'client') bg-green-100 text-green-800
            @elseif($contact->type == 'fournisseur') bg-blue-100 text-blue-800
            @elseif($contact->type == 'prestataire') bg-purple-100 text-purple-800
            @elseif($contact->type == 'ouvrier') bg-yellow-100 text-yellow-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ ucfirst($contact->type) }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $contact->name }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contact->email ?? '-' }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contact->phone }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <button onclick="openModal('edit', {{ $contact->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
            <i class="fas fa-edit"></i>
        </button>
        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirmDelete()">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
@endforeach