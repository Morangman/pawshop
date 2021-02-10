import swal from 'sweetalert';
import i18n from './i18n';

// todo: translations
const swalConfig = (data) => {
  return {
    title: i18n.t('common.phrase.confirm.title') || 'Are you sure?',
    text: i18n.t('common.phrase.confirm.body') || 'You will not be able to recover this',
    icon: data.type ||'warning',
    buttons: [
        i18n.t('common.word.cancel') || 'Cancel',
        i18n.t('common.word.confirm') || 'Yes, proceed!'
    ],
  };
};
const confirmation = {};

confirmation.delete = (
  success,
  data = {}
) => {
  if (!data.confirmButtonText) {
    data.confirmButtonText = 'Yes, delete it!';
  }

  swal(swalConfig(data)).then((result) => {
      if (result) {
          success();
      }
  });
};

confirmation.warning = (
  success,
  data = {}
) => {
    swal(swalConfig(data)).then((result) => {
        if (result) {
            success();
        }
    });
};

window.confirmation = confirmation;
