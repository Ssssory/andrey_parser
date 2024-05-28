import navigation.source as src
import navigation.page as page
import navigation.wait as wait
from config import initialize

    
if __name__ == '__main__':
    initialize()
    locatable_wait = wait.LocatableSourceWait()
    clickable_wait = wait.ClickableSourceWait()
    webpage = page.Page()
    cookie_popup = page.Popup(src.PopupXPaths.COOKIE_AGREEMENT, locatable_wait)
    private_data_popup = page.Popup(src.PopupXPaths.PERSONAL_DATA, locatable_wait)
    sortable_menu = page.Anchor(src.PageXPaths.SORT_MENU, clickable_wait)
    rent_filter_button = page.Button(src.PageXPaths.RENT_FILTER, locatable_wait)
    search_button = page.Button(src.PageXPaths.SEARCH_BUTTON, clickable_wait)
    
    webpage.load(src.Urls.HOME)
    rent_filter_button.click()
    search_button.click()
    sortable_menu.wait()
    cookie_popup.close()
    private_data_popup.close()

    #temporary for testing
    while True:
        pass
