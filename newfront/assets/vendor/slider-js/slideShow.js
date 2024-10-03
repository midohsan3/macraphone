//animation list: flip, slice, box3D, pixel, fade, glide, card

$(document).ready(function () {

    $('#slideWiz').slideWiz({
        auto: true,
        speed: 5000,
        row: 12,
        col: 35,
        animation: [
            'flip',
            'slice',
            'box3D',
           
            'fade',
            'glide',
            'card'
        ],
        file: [
            {
                src: {
                    main: "front/assets/image/silder/slider-2.jpg",
                    cover: "front/assets/image/silder/slider-1.jpg"
                },
                title: 'Title',
                desc: "Some text here for description Some text here for description Some text here for description Some text here for description Some text here for description",
                descLength: 220,
                button: {
                    text: 'Order Now',
                    url: '#',
                    class: 'btn btn-medium btn-primary'
                }
            },
            {
                src: {
                    main: "{{ url('front/assets/image/silder/slider-6.jpg') }}",
                    cover: "{{ url('front/assets/image/silder/slider-2.jpg') }}"
                },
                title: 'Title 2',
                desc: "Some text here for descriptionSome text here for descriptionSome text here for descriptionSome text here for description Some text here for description",
                button: {
                    text: 'Order Now',
                    url: '#',
                    class: 'btn btn-medium btn-primary'
                }
            },
            {
                src: {
                    main: "front/assets/image/silder/slider-1.jpg",
                    cover: "front/assets/image/silder/slider-3.jpg"
                },
                title: 'Title-3',
                desc: "Some text here for description Some text here for description Some text here for description Some text here for description Some text here for description",
                descLength: 190,
                button: {
                    text: 'Order Now',
                    url: '#',
                    class: 'btn btn-medium btn-primary'
                }
            },
            {
                src: {
                    main: "front/assets/image/silder/slider-2.jpg",
                    cover: "front/assets/image/silder/slider-6.jpg"
                },
                title: 'Title-5 ',
                desc: "Some text here for description Some text here for description Some text here for description Some text here for description",
                button: {
                    text: 'Order Now',
                    url: false,
                    class: 'btn btn-medium btn-primary'
                }
            }
        ]

    });

});
