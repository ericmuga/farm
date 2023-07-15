import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import {useLocation} from '@/Composables/useLocation.js'

export default function useFormSubmission(
  initialFormData,
  initialState = 'Create',
  onSubmissionComplete = () => {}
) {
  const showModal = ref(false);
  const submitting = ref(false);
  const form = useForm({
    ...initialFormData,
    id: null,
    state: initialState,
  });

  const fetchLocation = async () => {
    try {
      const { latitude, longitude } = await useLocation().getLocation();
      form.value.geoLocation = {latitude,longitude};
    } catch (error) {
      form.value.geoLocation={error};
    }
  };

  const submitForm = async (storeRoute, updateRoute) => {
    try {
      submitting.value = true;
      await fetchLocation();

      if (form.value.state === 'Create') {
        await form.value.post(route(storeRoute));
      } else {
        await form.value.patch(route(updateRoute, form.value.id));
      }

      submitting.value = false;
      showModal.value = false;
      form.value.reset();

      Swal.fire(`Item ${form.value.state}d Successfully!`, '', 'success');
      onSubmissionComplete(); // Invoke the callback on successful form submission
    } catch (error) {
      submitting.value = false;
      Swal.fire('Error!', 'An error occurred while saving the item.'+error, 'error');
      // You can also handle specific errors here if needed
    }
  };

  return {
    showModal,
    submitting,
    form,
    submitForm,
  };
}
