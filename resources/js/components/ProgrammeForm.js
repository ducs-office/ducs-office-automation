import axios from 'axios';
import debounce from 'lodash/debounce';
export default {
    props: {
        old: { default: null },
        model: { default: null },
    },
    data() {
        return {
            courses: [],
            form: {
                code: '',
                name: '',
                wef: '',
                type: '',
                duration: 3,
                semester_courses: {}
            }
        };
    },
    watch: {
        'form.code': debounce(async function(code){
            const {data} = await axios.get(
                '/api/courses', { params: { q: code }}
            );

            this.courses = data;
        }, 300)
    },
    methods: {
        initFormField(field, fallback) {
            this.form[field] = (this.old && this.old[field])
                || (this.model && this.model[field])
                || fallback;
        },
        initForm() {
            this.initFormField('code', '')
            this.initFormField('name', '')
            this.initFormField('wef', '')
            this.initFormField('type', '')
            this.initFormField('duration', 3)
            this.initFormField('semester_courses', {})
        }
    },
    created() {
        this.initForm();
    }
};
