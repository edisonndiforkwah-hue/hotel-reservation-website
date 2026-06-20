<div class="ajax-search-container" style="padding: 40px 0; background: #f9f9f9;">
    <div class="container">
        <style>
            .search-bar-wrapper {
                background: #fff; 
                border: 2px solid #ccc; 
                border-radius: 12px; 
                display: flex; 
                align-items: center; 
                padding: 10px 20px; 
                box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
                transition: border-color 0.2s;
                flex-wrap: nowrap; /* Force sur une seule ligne */
            }
            .search-item {
                display: flex;
                align-items: center;
                padding: 0 15px;
            }
            .search-item-dest {
                flex-grow: 2;
                padding-left: 0;
            }
            .search-item-filter {
                flex-grow: 1;
                border-left: 1px solid #eee;
            }
            .search-input-group {
                display: flex; 
                flex-direction: column; 
                width: 100%;
            }
            .search-label {
                font-size: 0.85rem; 
                color: #666; 
                margin-bottom: 2px; 
                font-weight: normal;
                white-space: nowrap;
            }
            .search-input {
                border: none; 
                outline: none; 
                font-size: 1rem; 
                font-weight: bold; 
                color: #000; 
                width: 100%; 
                background: transparent;
            }
            @media (max-width: 992px) {
                .search-bar-wrapper { flex-wrap: wrap; }
                .search-item-filter { border-left: none; border-top: 1px solid #eee; padding-top: 10px; margin-top: 10px; width: 100%; }
            }
        </style>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="search-bar-wrapper">
                    
                    <!-- Search Input -->
                    <div class="search-item search-item-dest">
                        <i class="fa fa-search" style="font-size: 20px; color: #333; margin-right: 15px;"></i>
                        <div class="search-input-group">
                            <label for="ajax-destination" class="search-label">Destination / Keyword</label>
                            <input type="text" id="ajax-destination" class="search-input" placeholder="Where to?">
                        </div>
                        <!-- Clear Button -->
                        <div id="clear-search" style="font-size: 20px; color: #333; cursor: pointer; display: none;">
                            <i class="fa fa-times"></i>
                        </div>
                    </div>

                    <!-- Room Type Filter -->
                    <div class="search-item search-item-filter">
                        <div class="search-input-group">
                            <label for="ajax-type" class="search-label">Room Type</label>
                            <select id="ajax-type" class="search-input" style="cursor: pointer;">
                                <option value="">All Types</option>
                                @if(isset($roomTypes))
                                    @foreach($roomTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Max Price Filter -->
                    <div class="search-item search-item-filter">
                        <div class="search-input-group">
                            <label for="ajax-price" class="search-label">Max Price ($)</label>
                            <input type="number" id="ajax-price" class="search-input" placeholder="Any price">
                        </div>
                    </div>

                    <!-- Favorites Filter -->
                    <div class="search-item search-item-filter" style="justify-content: center;">
                        <label style="display: flex; align-items: center; cursor: pointer; margin: 0; font-weight: bold; color: #333; font-size: 1rem; white-space: nowrap;">
                            <input type="checkbox" id="ajax-favorites" style="margin-right: 8px; width: 18px; height: 18px; cursor: pointer;">
                            <i class="fa fa-heart" style="color: #ff0000; margin-right: 5px;"></i> Favorites
                        </label>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Fonction globale pour récupérer les favoris du localStorage
function getFavorites() {
    let favs = localStorage.getItem('hotel_favorites');
    return favs ? JSON.parse(favs) : [];
}

function toggleFavorite(roomId, element) {
    let favs = getFavorites();
    let index = favs.indexOf(roomId.toString());
    
    if (index === -1) {
        favs.push(roomId.toString());
        $(element).css('color', '#ff0000'); // Rouge
    } else {
        favs.splice(index, 1);
        $(element).css('color', '#ffffff'); // Blanc/Gris
    }
    
    localStorage.setItem('hotel_favorites', JSON.stringify(favs));
    
    // Si on est en train de filtrer par favoris, rafraîchir la liste
    if ($('#ajax-favorites').is(':checked')) {
        triggerSearch();
    }
}

// Fonction pour mettre à jour l'affichage des coeurs après un chargement AJAX
function updateHeartsUI() {
    let favs = getFavorites();
    $('.fav-btn').each(function() {
        let roomId = $(this).data('id').toString();
        if (favs.includes(roomId)) {
            $(this).css('color', '#ff0000');
        } else {
            $(this).css('color', '#ffffff');
        }
    });
}

function triggerSearch() {
    let query = $('#ajax-destination').val();
    let type = $('#ajax-type').val();
    let price = $('#ajax-price').val();
    let onlyFavorites = $('#ajax-favorites').is(':checked');
    let favoriteIds = onlyFavorites ? getFavorites() : [];

    if (onlyFavorites && favoriteIds.length === 0) {
        // Aucun favori, vider la grille
        $('#rooms-grid-container').html('<div class="col-md-12"><div class="alert alert-warning">You have no favorites yet. Click the heart icon on a room to add it.</div></div>');
        return;
    }

    $.ajax({
        url: "{{ route('search_rooms_ajax') }}",
        type: "GET",
        data: { 
            'query': query,
            'type': type,
            'price': price,
            'favorites': favoriteIds
        },
        success: function(data) {
            $('#rooms-grid-container').html(data);
            updateHeartsUI(); // Mettre à jour les coeurs sur les nouveaux éléments
        }
    });
}

$(document).ready(function() {
    const searchInput = $('#ajax-destination');
    const typeSelect = $('#ajax-type');
    const priceInput = $('#ajax-price');
    const favCheckbox = $('#ajax-favorites');
    const clearBtn = $('#clear-search');

    // Mettre en évidence la bordure au focus
    searchInput.on('focus', function() {
        $('.search-bar-wrapper').css('border-color', '#0071c2');
    }).on('blur', function() {
        $('.search-bar-wrapper').css('border-color', '#ccc');
    });

    // Écouteurs d'événements
    searchInput.on('keyup', function() {
        if ($(this).val().length > 0) {
            clearBtn.show();
        } else {
            clearBtn.hide();
        }
        triggerSearch();
    });

    typeSelect.on('change', triggerSearch);
    priceInput.on('keyup change', triggerSearch);
    favCheckbox.on('change', triggerSearch);

    // Effacer la recherche
    clearBtn.on('click', function() {
        searchInput.val('');
        $(this).hide();
        triggerSearch();
        searchInput.focus();
    });

    // Init UI
    updateHeartsUI();
});
</script>
